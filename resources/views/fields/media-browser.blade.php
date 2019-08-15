<input type="hidden" name="{{ str_replace('[]', '', $name) }}">

<media-modal
  id="{{$id}}"
  name="{{ $name }}"
  :is-many-relation="{{ $isManyRelation ? 'true' : 'false' }}"
  :data='{!! $data !!}'
></media-modal>
{{--
<div class="js-media-browser-{{ $id }}">

  <div data-bind="with: selectedMedia">
    <ul class="secret-list media-browser" data-bind="foreach: media">
      <li class="masonry-item masonry-item-inline-media">
        <input type="checkbox" class="media-is-used" name="{{ $name }}" checked data-bind="
          value: id, attr: { id: '{{ $id }}-' + id }
        ">
        <label data-bind="attr: { for: '{{ $id }}-' + id }">
          <img data-bind="attr: { src: $parent.getPreviewImageUrl(filename, type, private) }" width="150">
          <p class="icons" data-bind="text: caption"></p>
        </label>
      </li>
    </ul>
  </div>

  <span class="btn btn-success fileinput-button">
    <span class="js-progress inline-progress-bar" data-progress=""></span>
    <i class="fa fa-plus"></i>
    <span>Upload New Media</span>
    <input class="js-file-upload-{{ $id }}" type="file" name="files[]" multiple>
  </span>

  <button class="btn btn-info" data-bind="click: mediaLibrary.browse"><i class="fa fa-search"></i> Browse Media</button>
  <br>
  <br>

  <script>
    $('.js-media-browser-{{ $id }}').data('values', {!! $data !!});
  </script>


  @include('admin::fields.partials.media-modal')

</div> --}}
