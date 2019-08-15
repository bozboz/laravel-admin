<template>
  <div>
    <modal title="Edit file" v-model="editingFile.show" @close="cancelEditing" size="">
      <div class="form-group">
        <label for="caption">Caption</label>
        <input type="text" class="form-control" id="caption" v-model="editingFile.caption">
      </div>
      <template v-slot:footer>
        <button type="submit" class="btn btn-primary" @click.prevent="updateFile(editingFile)">Save</button>
      </template>
    </modal>
    <modal v-model="showModal" @close="showModal = false" cancelText="Close">
      <template v-slot:header>
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" :class="{'active': tab === 'upload'}" @click="tab = 'upload'">
            <a role="tab">
              <span class="icon is-small"><i class="fa fa-upload"></i></span>
              <span>Upload</span>
            </a>
          </li>
          <li role="presentation" :class="{'active': tab === 'browse'}" @click="tab = 'browse'">
            <a role="tab">
              <span class="icon is-small"><i class="fa fa-folder-open"></i></span>
              <span>Browse</span>
            </a>
          </li>
        </ul>
      </template>
      <uploader :showTitle="false" v-if="tab === 'upload'"></uploader>
      <media-browser
        v-else
        @selectFile="selectFile"
        :highlightFiles="selectedFiles"
      ></media-browser>
    </modal>

    <slick-list class="tiles" v-model="selectedFiles" axis="xy" :distance="5">
      <slick-item v-for="(file, index) in selectedFiles"
        :key="file.id"
        :index="index"
        class="file-drop col-lg-2 col-md-3 col-sm-6"
      >
        <file
          :file="file"
          :canDelete="true"
          :canDrag="false"
          class="file"
          @delete="deselectFile(file)"
          @select="editFile(file)"
        >
          <input type="hidden" :name="name" v-model="file.id">
        </file>
      </slick-item>
      <div class="col-lg-2 col-md-3 col-sm-6" v-if="selectedFiles.length<1">
        <div class="panel panel-default">
          <div class="panel-body" style="font-size: 4em; text-align:center; color: silver">
            <i class="fa fa-picture-o"></i>
          </div>
        </div>
      </div>
    </slick-list>

    <button @click.prevent="showModal = true"
      class="btn btn-primary"
      v-if="canSelect"
    >Select Media</button>
    <div v-else>
      <button @click.prevent="showModal = true"
        class="btn btn-info"
      >Change</button>
      <button @click.prevent="deselectFile(selectedFiles[0])"
        class="btn btn-danger"
      >Remove</button>
    </div>
  </div>
</template>

<script>
import { SlickList, SlickItem } from 'vue-slicksort';
import { setTimeout } from 'timers';
import Modal from './Modal';
import MediaBrowser from './MediaBrowser';
import Uploader from './Uploader';
import File from './File';
import EditModal from './EditModal';

export default {
  components: {
    SlickList,
    SlickItem,
    Modal,
    MediaBrowser,
    Uploader,
    File,
    EditModal,
  },
  props: {
    isManyRelation: {
      type: Boolean,
    },
    data: {
      type: Object,
      default: {
        media: [],
      },
    },
    name: {
      type: String,
    },
  },
  data() {
    return {
      showModal: false,
      selectedFiles: [],
      editingFile: {},
      tab: 'upload',
    };
  },
  watch: {
    showModal(newValue) {
    },
  },
  computed: {
    canSelect() {
      return this.isManyRelation || (!this.isManyRelation && this.selectedFiles.length === 0);
    }
  },
  methods: {
    selectFile(file) {
      const exists = this.selectedFiles.findIndex(f => f.id === file.id) >= 0;
      if (exists) {
        this.deselectFile(file);
        return;
      }

      if (!this.canSelect) {
        this.selectedFiles.shift();
      }

      file.over = false;
      this.selectedFiles.push(file);

      if (!this.isManyRelation) {
        this.showModal = false;
      }
    },
    deselectFile(file) {
      this.selectedFiles.splice(this.selectedFiles.findIndex(f => f.id === file.id), 1);
    },
    sort() {
      console.log(arguments);
    },
    editFile(file) {
      console.log(file);

      this.editingFile = file;
      this.editingFile.show = true;
    },
    cancelEditing() {
      this.editingFile = {};
    },
    updateFile(file) {
      const formData = new FormData();
      formData.append('caption', file.caption);
      window.axios.post('/admin/files/edit/' + file.id, formData)
        .then(response => {
          if (response.data.success === true) {
            this.showNotification('File successfully updated', true, () => this.updateFile(response.data.original));
          }
          this.editingFile = {};
        })
        .catch(error => {
          console.log(error);
          if (!error.response) {
            return;
          }

          this.showNotification(error.response.data.message, false);
          for (const field in error.response.data) {
            if (error.response.data.hasOwnProperty(field)) {
            const message = error.response.data[field];
            this.showNotification(message, false);
            }
          }
        });
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
      });
    },
    resetModal() {
      console.log(this, this.showModal);
      this.showModal = false;

      setTimeout(() => {
        this.showModal = true;
        console.log(this, this.showModal);

      }, 1);
    },
  },
  mounted() {
    this.selectedFiles = this.data.media;
    this.selectedFiles.map(file => file.over = false);
  }
};
</script>

<style scoped>
  .file {
    height: 100%;
  }
  .file::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0,0,0,.5);
    opacity: 0;
    pointer-events: none;
    transition: .2s opacity;
  }
  .is-dropping .file::after {
    opacity: 1;
  }
  .file-drop {
    height: 100%;
  }
  .nav-tabs {
    position: relative;
    bottom: -15px;
    border: 0;
  }

  .tiles {
  display: flex;
  flex-wrap: wrap;
  }
  @supports(display: grid) {
    .tiles {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(20rem, 1fr));
    }
    .tiles > * {
      width: 100%;
    }
    .tiles::before,
    .tiles::after {
      display: none;
    }
  }
</style>