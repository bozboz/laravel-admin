<div class="modal fade media-modal js-media-modal-{{ $name }}" data-bind="modal: mediaLibrary.active">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Browse Media</h4>
      </div>
      <div class="modal-body" data-bind="with: mediaLibrary">
        <ul class="secret-list" data-bind="foreach: media, masonry: media">
          <li class="masonry-item masonry-item-inline-media">
            <input type="checkbox" class="media-is-used" data-bind="
              checked: $parent.currentMedia, value: $data, attr: { id: 'library-{{ $name }}-' + id }
            ">
            <label data-bind="attr: { for: 'library-{{ $name }}-' + id }">
              <img data-bind="attr: { src: type === 'pdf' ? '/packages/bozboz/media-library/images/document.png' : $parent.getFilename(filename) }" width="150">
              <p class="icons" data-bind="text: caption"></p>
            </label>
          </li>
        </ul>
       </div>
      <div class="modal-footer">
        <div class="pagination" data-bind="html: mediaLibrary.links"></div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="update-media" data-bind="click: selectedMedia.update">Select Media</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->  