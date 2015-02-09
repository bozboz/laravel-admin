<input type="hidden" name="{{ $name }}">

<div class="js-media-browser-{{ $id }}">

  <div data-bind="with: selectedMedia">
    <ul class="secret-list media-browser" data-bind="foreach: media">
      <li class="masonry-item masonry-item-inline-media">
        <input type="checkbox" class="media-is-used" name="{{ $name }}" checked data-bind="
          value: id, attr: { id: '{{ $id }}-' + id }
        ">
        <label data-bind="attr: { for: '{{ $id }}-' + id }">
          <img data-bind="attr: { src: type === 'pdf' ? '/packages/bozboz/media-library/images/document.png' : $parent.getFilename(filename) }" width="150">
          <p class="icons" data-bind="text: caption"></p>
        </label>
      </li>
    </ul>
  </div>

  <script>
  $('.js-media-browser-{{ $id }}').data('values', {{ $data }});
  </script>

  <button class="btn btn-info" data-bind="click: mediaLibrary.browse">Browse Media</button>

  @include('admin::fields.partials.media-modal')

</div>
