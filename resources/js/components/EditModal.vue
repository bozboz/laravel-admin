<template>
  <modal title="Edit file" v-model="show" @close="close">
    <form @submit.prevent="onEditorFile" ref="editForm">
      <div class="form-group" v-if="!hideFields.includes('name')">
        <label for="name">Name</label>
        <input type="text" class="form-control" required id="name" :readonly="file.exists" placeholder="Please enter a file name" v-model="file.name">
      </div>
      <div class="form-group" v-if="!hideFields.includes('caption')">
        <label for="caption">Caption</label>
        <input type="text" class="form-control" id="caption" v-model="file.caption">
      </div>
      <div class="form-group" v-if="!hideFields.includes('tags')">
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
      <div class="form-group" v-if="!hideFields.includes('folder')">
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
      <div class="form-group" v-if="file.id && file.blob && file.type && file.type.substr(0, 6) === 'image/' &&  !hideFields.includes('cropper')">
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
        <div class="form-group" v-if="!hideFields.includes('file')">
          <label>File</label>
          <a :href="'/media/' + file.type + '/' + file.filename" target="_blank">
            {{file.filename}}
          </a>
        </div>
        <div class="form-group" v-if="!hideFields.includes('file')">
          <label for="file">Update File</label>
          <input type="file" id="file" @change="changeEditingFile">
        </div>
        <div class="form-group image-preview" v-if="file.type === 'image' && !hideFields.includes('preview')">
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
import { mapState } from 'vuex';

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
  computed: {
    ...mapState({
      value: state => state.EditFile.file,
      error: state => state.EditFile.error,
      originalFile: state => state.EditFile.originalFile,
      hideFields: state => state.EditFile.hideFields,
    }),
    show() {
      return this.file && this.file.name && this.file.name.length > 0;
    },
  },
  watch: {
    value(newValue) {
      if (!newValue) {
        return;
      }
      const file = { ...newValue };

      if (file.folder_id) {
        file.folder = this.options.folders.filter(folder => folder.id === newValue.folder_id);
      }
      if (file.tags) {
        file.tags = newValue.tags.map(name => ({name: name}));
      }

      this.file = file;

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
    },
    error(error) {
      if (!error) {
        return;
      }
      this.showNotification(error.response.data.message, false);
      for (const field in error.response.data) {
        if (error.response.data.hasOwnProperty(field)) {
          const message = error.response.data[field];
          this.showNotification(message, false);
        }
      }
    },
    originalFile(original) {
      if (!original) {
        return;
      }
      this.showNotification('File successfully updated', true, () => this.update(original));
    }
  },
  mounted() {
    window.axios.get('/admin/media/tags').then(response => this.options.tags = response.data);
    window.axios.get('/admin/media/folder/options').then(response => this.options.folders = response.data);
  },
  methods: {
    onEditorFile() {
      let data = {
        id: this.file.id,
        filename: this.file.name,
        caption: this.file.caption,
      }

      if ({ ...this.file }.hasOwnProperty('tags')) {
        data.tags = this.file.tags.map(tag => tag.name) || [];
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

      if (this.file.exists) {
        this.update(data);
      } else {
        this.$store.dispatch('EditFile/setUpdatedFile', data);
      }
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
    close() {
      this.$store.dispatch('EditFile/end');
    },
    update(file) {
      this.$store.dispatch('EditFile/update', file)
    },
    showNotification(text, success, undoAction = null) {
      const actions = [
        {
          text: '×',
          onClick: (e, toast) => toast.goAway(),
          class: 'toast-action toast-action--dismiss',
        },
      ];

      if (undoAction) {
        actions.unshift({
          text: 'Undo',
          class: 'toast-action',
          onClick: (e, toast) => {
            toast.goAway(0);
            undoAction(toast);
          },
        });
      }

      this.$toasted.show(text, {
        duration: 15000,
        type: success ? 'success' : 'error',
        position: 'bottom-right',
        iconPack: 'fontawesome',
        action: actions,
        icon: success ? 'check' : 'exclamation-triangle',
        singleton: true,
      })
    },
  },
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style src="cropperjs/dist/cropper.min.css"></style>
<style>
.image-preview {
  --background-color: rgba(255, 255, 255, 1);
  --a: 0.3;
  --b: 0.0;
  --c: var(--a);
  --d: var(--b);
  --e: 0.5;
  display: block;
  background-color: var(--background-color);
  background-size: 25px 25px;
  background-image:
                    linear-gradient(to right, rgba(0, 0, 0, var(--a)) 50%, transparent 50%, transparent),
                    linear-gradient(to right, transparent 0%, transparent 50%, rgba(0, 0, 0, var(--b)) 50%),
                    linear-gradient(to bottom, rgba(0, 0, 0, var(--c)) 50%, transparent 50%, transparent),
                    linear-gradient(to bottom, transparent 0%, transparent 50%, rgba(0, 0, 0, var(--d)) 50%),

                    /* draw squares to selectively shift opacity */
                    linear-gradient(to bottom, var(--background-color) 50%, transparent 50%, transparent),
                    linear-gradient(to right, transparent 0%, transparent 50%, rgba(0, 0, 0, var(--e)) 50%),
                    
                    /* b-i: l-g toggle trick */
                    none;
}
</style>
