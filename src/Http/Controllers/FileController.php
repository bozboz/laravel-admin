<?php
namespace Bozboz\Admin\Http\Controllers;

use Bozboz\Admin\Media\Tag;
use Illuminate\Http\Request;
use Bozboz\Admin\Media\Media;
use Bozboz\Permissions\RuleStack;
use Illuminate\Routing\Controller;
use Bozboz\Admin\Media\MediaFolder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Validation\ValidatesRequests;

class FileController extends Controller
{
    use ValidatesRequests;

    private $image_ext = ['jpg', 'jpeg', 'png', 'gif'];
    private $audio_ext = ['mp3', 'ogg', 'mpga'];
    private $video_ext = ['mp4', 'mpeg'];
    private $document_ext = ['doc', 'docx', 'pdf', 'odt'];

    public function main()
    {
        if (!$this->canView()) {
            return abort(403);
        }
        return view('admin::media.main', [
            'canCreate' => $this->canCreate(),
            'canEdit' => $this->canEdit(),
            'canDelete' => $this->canDestroy(),
        ]);
    }

    public function uploader()
    {
        return view('admin::media.upload');
    }

    public function js()
    {
        return response(file_get_contents(base_path('vendor/bozboz/admin/resources/js/dist/app.js')))
            ->header('Content-Type', 'application/javascript');
    }

    /**
     * Fetch files by Type or Id
     * @param  string $type  Media type
     * @param  integer $id   Media Id
     * @return object        Files list, JSON
     */
    public function index($type, $id = null)
    {
        if (!$this->canView()) {
            return abort(403);
        }
        $model = new Media();

        if (!is_null($id)) {
            $response = $model::findOrFail($id);
        } else {
            $records_per_page = 24;

            if (! request()->input('page') || request()->input('page') == 1 || request()->input('page') == 'undefined') {
                $query = MediaFolder::orderBy('name');
                if (request()->input('search')) {
                    $query->where('name', 'LIKE', '%' . request()->input('search') . '%');
                } else {
                    if (request()->input('folder')) {
                        $query->where('parent_id', request()->input('folder'));
                    } else {
                        $query->whereNull('parent_id');
                    }
                }
                $folders = $query->get();
            } else {
                $folders = [];
            }

            $query = $model::where('type', $type)
                ->with('tags')
                ->orderBy('id', 'desc');

            if (request()->input('search')) {
                $query->where(function ($query) {
                    $query->orWhere('filename', 'LIKE', '%' . request()->input('search') . '%');
                    $query->orWhere('caption', 'LIKE', '%' . request()->input('search') . '%');
                });
            } else {
                if (request()->input('folder')) {
                    $query->where('folder_id', request()->input('folder'));
                } else {
                    $query->whereNull('folder_id');
                }
            }

            if (request()->input('tags')) {
                $query->whereHas('tags', function($query) {
                    $query->whereIn('name', request()->input('tags'));
                });
            }

            $files = $query->paginate($records_per_page);

            $response = [
                'pagination' => [
                    'total' => $files->total(),
                    'per_page' => $files->perPage(),
                    'current_page' => $files->currentPage(),
                    'last_page' => $files->lastPage(),
                    'from' => $files->firstItem(),
                    'to' => $files->lastItem()
                ],
                'data' => $files,
                'folders' => $folders,
            ];
        }

        return response()->json($response);
    }

    public function getTags()
    {
        if (!$this->canView()) {
            return abort(403);
        }
        return response()->json(
            Tag::orderBy('name')->get()
        );
    }

    /**
     * Upload new file and store it
     * @param  Request $request Request with form data: filename and file info
     * @return boolean          True if success, otherwise - false
     */
    public function store(Request $request)
    {
        if (!$this->canCreate()) {
            return abort(403);
        }
        $max_size = (int)ini_get('upload_max_filesize') * 1000;
        $all_ext = implode(',', $this->allExtensions());

        debug($request->all());

        $this->validate($request, [
            'name' => 'required|unique:media,filename',
            'file' => 'required|file|mimes:' . $all_ext . '|max:' . $max_size,
            'folder_id' => 'int|exists:media_folders,id',
        ]);

        $instance = new Media([
            'folder_id' => $request->input('folder_id'),
            'caption' => $request->input('caption'),
        ]);

        $file = $request->file('file');

        app(\Bozboz\Admin\Services\Uploader::class)->upload($file, $instance, $request->input('name'));

        if ($request->has('tags')) {
            $tags = collect($request->input('tags'))->map(function($value) {
                return Tag::firstOrCreate([
                    'name' => $value
                ])->id;
            });
            $instance->tags()->sync($tags->all());
        }

        $instance->save();

        return $instance;
    }

    /**
     * Edit specific file
     * @param  integer  $id      Media Id
     * @param  Request $request  Request with form data: filename
     * @return boolean           True if success, otherwise - false
     */
    public function update($id, Request $request)
    {
        if (!$this->canEdit()) {
            return abort(403);
        }
        $instance = Media::findOrFail($id)->load('tags');

        $original = $instance->getOriginal();

        $max_size = (int)ini_get('upload_max_filesize') * 1000;
        $all_ext = implode(',', $this->allExtensions());

        $this->validate($request, [
            'file' => 'file|mimes:' . $all_ext . '|max:' . $max_size,
            'folder_id' => 'int|exists:media_folders,id',
            'tags' => 'array',
        ]);

        $instance->caption = $request->input('caption');
        $instance->folder_id = $request->input('folder_id') ?: null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            app(\Bozboz\Admin\Services\Uploader::class)->replace($file, $instance);
        }

        if ($request->has('tags')) {
            $tags = collect($request->input('tags'))->filter(function ($tag) {
                return !is_null($tag) && $tag !== '';
            })->map(function($value) {
                return Tag::firstOrCreate([
                    'name' => $value
                ])->id;
            });
            $instance->tags()->sync($tags->all());
        }

        $instance->save();

        return response()->json([
            'success' => true,
            'original' => $original,
        ]);
    }


    /**
     * Delete file from disk and database
     * @param  integer $id  Media Id
     * @return boolean      True if success, otherwise - false
     */
    public function destroy($id)
    {
        if (!$this->canDestroy()) {
            return abort(403);
        }
        $file = Media::findOrFail($id);
        return response()->json($file->delete());
    }


    /**
     * Get type by extension
     * @param  string $ext Specific extension
     * @return string      Type
     */
    private function getType($ext)
    {
        if (in_array($ext, $this->image_ext)) {
            return 'image';
        }

        if (in_array($ext, $this->audio_ext)) {
            return 'audio';
        }

        if (in_array($ext, $this->video_ext)) {
            return 'video';
        }

        if (in_array($ext, $this->document_ext)) {
            return 'document';
        }
    }

    /**
     * Get all extensions
     * @return array Extensions of all file types
     */
    private function allExtensions()
    {
        return array_merge($this->image_ext, $this->audio_ext, $this->video_ext, $this->document_ext);
    }

    public function canView()
    {
        return $this->isAllowed('view');
    }

    public function canCreate($instance = null)
    {
        return $this->isAllowed('create', $instance);
    }

    public function canEdit($instance = null)
    {
        return $this->isAllowed('edit', $instance);
    }

    public function canDestroy($instance = null)
    {
        return $this->isAllowed('delete', $instance);
    }

    private function isAllowed($action, $instance = null)
    {
        $stack = new RuleStack;

        $stack->add($action . '_anything');

        $this->{$action . 'Permissions'}($stack, $instance);

        return $stack->isAllowed();
    }

    protected function viewPermissions($stack)
    {
        $stack->add('view_media');
    }

    protected function createPermissions($stack)
    {
        $stack->add('create_media');
    }

    protected function editPermissions($stack, $instance)
    {
        $stack->add('edit_media');
    }

    protected function deletePermissions($stack, $instance)
    {
        $stack->add('delete_media');
    }
}
