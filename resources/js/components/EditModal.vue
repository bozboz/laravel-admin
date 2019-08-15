<template>
  <modal title="Edit file" v-model="file.show" @close="$emit('close')">
    <form @submit.prevent="onEditorFile" ref="editForm">
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" required id="name" :readonly="file.exists" placeholder="Please enter a file name" v-model="file.name">
      </div>
      <div class="form-group">
        <label for="caption">Caption</label>
        <input type="text" class="form-control" id="caption" v-model="file.caption">
      </div>
      <div class="form-group">
        <label for="tagsSelect">Tags</label>
        <multiselect
          v-model="file.tags"
          tag-placeholder="Add this as new tag"
          placeholder=""
          label="name"
          track-by="name"
          id="tagsSelect"
          :tabindex="0"
          :options="options.tags"
          :multiple="true"
          :taggable="true"
          @tag="addTag"
        ></multiselect>
      </div>
      <div class="form-group">
        <label for="folder">Folder</label>
        <multiselect
          v-model="file.folder"
          placeholder=""
          label="name"
          track-by="id"
          id="folder"
          :tabindex="0"
          :options="options.folders"
          :multiple="false"
        >
          <template slot="option" slot-scope="props">
            <span v-html="props.option.indentedName"></span>
          </template>
        </multiselect>
      </div>
      <div class="form-group" v-if="file.show && file.blob && file.type && file.type.substr(0, 6) === 'image/'">
        <label>Image</label>
        <div class="edit-image">
          <img :src="file.blob" ref="editImage" />
        </div>
        <div class="edit-image-tool">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" @click="file.cropper.rotate(-90)" title="cropper.rotate(-90)"><i class="fa fa-undo" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-primary" @click="file.cropper.rotate(90)"  title="cropper.rotate(90)"><i class="fa fa-repeat" aria-hidden="true"></i></button>
          </div>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-primary" @click="file.cropper.crop()" title="cropper.crop()"><i class="fa fa-check" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-primary" @click="file.cropper.clear()" title="cropper.clear()"><i class="fa fa-remove" aria-hidden="true"></i></button>
          </div>
        </div>
      </div>
      <div v-if="file.exists">
        <div class="form-group">
          <label>File</label>
          <a :href="'/media/' + file.type + '/' + file.filename" target="_blank">
            {{file.filename}}
          </a>
        </div>
        <div class="form-group">
          <label for="file">Update File</label>
          <input type="file" id="file" @change="changeEditingFile">
        </div>
        <div class="form-group" v-if="file.type === 'image'">
          <img v-if="file.preview" class="img-responsive" :src="file.preview">
          <img v-else class="img-responsive" :src="'/media/' + file.type + '/' + file.filename" :alt="file.filename">
        </div>
      </div>
    </form>
    <template v-slot:footer>
      <button type="submit" class="btn btn-primary" @click.prevent="onEditorFile">Save</button>
    </template>
  </modal>
</template>

<script>
import Cropper from 'cropperjs'
import Multiselect from 'vue-multiselect';

import Modal from './Modal';

export default {
  components: {
    Modal,
    Multiselect,
  },
  data() {
    return {
      file: {},
      options: {
        tags: [],
        folders: [],
      },
    };
  },
  props: {
    value: {
      type: Object,
      default: {},
    },
  },
  watch: {
    value(newValue) {
      if (!newValue) {
        return;
      }
      if (newValue.folder_id) {
        newValue.folder = this.options.folders.filter(folder => folder.id === newValue.folder_id);
      }
      if (newValue.tags) {
        newValue.tags = newValue.tags.map(name => ({name: name}));
      }

      this.file = newValue;

      this.$nextTick(() => {
        if (this.$refs.editImage) {
          let cropper = new Cropper(this.$refs.editImage, {
            autoCrop: false,
          });
          this.file = {
            ...this.file,
            cropper
          }
        }
      });
    }
  },
  mounted() {
    window.axios.get('/admin/files/tags').then(response => this.options.tags = response.data);
    window.axios.get('/admin/files/folder/options').then(response => this.options.folders = response.data);
  },
  methods: {
    onEditorFile() {
      let data = {
        filename: this.file.name,
        caption: this.file.caption,
      }
      if (this.file.tags) {
        data.tags = this.file.tags.map(tag => tag.name);
      }
      if (this.file.folder) {
        data.folder_id = this.file.folder.id;
      }

      if (this.file.cropper) {
        let binStr = atob(this.file.cropper.getCroppedCanvas().toDataURL(this.file.type).split(',')[1])
        let arr = new Uint8Array(binStr.length)
        for (let i = 0; i < binStr.length; i++) {
          arr[i] = binStr.charCodeAt(i)
        }
        data.file = new File([arr], data.name, { type: this.file.type })
        data.size = data.file.size
      }
      this.$emit('save', this.file);
    },
    changeEditingFile(e) {
      this.file.file = e.target.files[0];
      const reader = new FileReader();

      reader.onload = (e) => {
        this.file.preview = e.target.result;
        this.$forceUpdate();
      }

      reader.readAsDataURL(this.file.file);
    },
    addTag(name) {
      const newTag = {name: name};
      this.options.tags.push(newTag);

      if (!this.file.tags) {
        this.file.tags = [];
      }
      this.file.tags.push(newTag);
    },
  },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="cropperjs/dist/cropper.min.css"></style>
