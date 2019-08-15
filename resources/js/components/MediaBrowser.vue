<template>
<div>
  <!-- File form -->
  <div v-if="canCreate">
    <a href="/admin/files/upload" class="btn-success pull-right btn btn-sm new-btn">New Media <i class="fa fa-plus"></i></a>
    <form id="new-folder-form" class="form-inline pull-right" style="clear:right;" action="#" method="#" @submit.prevent="newFolder">
      <label for="new_folder">New Folder</label>
      <div class="input-group">
        <input class="form-control input-sm" type="text" id="new_folder" name="name" placeholder="Folder name" v-model="folderName" required>
        <div class="input-group-btn">
          <button type="submit" class="btn btn-primary btn-sm">
            Add new folder
          </button>
        </div>
      </div>
    </form>
  </div>


  <!-- Confirm -->
  <modal title="Are you sure?" v-model="showConfirm" @close="cancelDeleting()" size="sm">
    <p>Are you sure you want to continue?</p>

    <template v-slot:footer>
      <button class="btn btn-primary" @click="deleteFile()">
        Confirm
      </button>
    </template>
  </modal>

  <!-- Main -->
  <div class="container-fluid" style="margin-bottom: 1em">
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" :class="{'active': isActive('image')}" @click="getFiles('image')">
        <a role="tab">
          <span class="icon is-small"><i class="fa fa-image"></i></span>
          <span>Pictures</span>
        </a>
      </li>
      <li role="presentation" :class="{'active': isActive('pdf')}" @click="getFiles('pdf')">
        <a role="tab">
          <span class="icon is-small"><i class="fa fa-file-text-o"></i></span>
          <span>PDF</span>
        </a>
      </li>
      <li role="presentation" :class="{'active': isActive('misc')}" @click="getFiles('misc')">
        <a role="tab">
          <span class="icon is-small"><i class="fa fa-file"></i></span>
          <span>Misc</span>
        </a>
      </li>
    </ul>
  </div>

  <div class="tabs-details">
    <div class="container-fluid text-center tiles">
    <div class="col-lg-2 col-md-3 col-sm-6" v-if="currentFolder" v-cloak>
      <drop
        @drop="moveIntoFolder({ id: currentFolder.parent_id }, ...arguments)"
        @dragover="upFolderOver = true"
        @dragleave="upFolderOver = false"
        :class="{'panel': true, 'panel-info': true, 'is-dropping': upFolderOver}"
      >
      <div class="card-image">
        <div class="panel-body">
        <a class="text-center center-block" style="font-size: 4em" @click="upFolder(currentFolder)">
          <i class="fa fa-arrow-left"></i>
        </a>
        « Back
        </div>
      </div>
      </drop>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6" v-for="folder in folders" :key="folder.id" v-cloak>
      <drop
        @drop="moveIntoFolder(folder, ...arguments)"
        @dragover="folder.over = true"
        @dragleave="folder.over = false"
        :class="{'panel': true, 'panel-primary': true, 'is-dropping': folder.over}"
      >
      <drag
      :draggable="canEdit"  class="card-image" :transfer-data="{type: 'folder', folder: folder}">
        <button v-if="canDelete" class="btn btn-danger btn-sm delete-btn" title="Delete" @click="prepareToDeleteFolder(folder)">
          <i class="fa fa-trash"></i> Delete
        </button>
        <div class="panel-body">
        <a class="text-center center-block" style="font-size: 4em" @click="openFolder(folder)">
          <i class="fa fa-folder"></i>
        </a>
        <input class="form-control input-sm" v-if="folder === editingFolder" v-autofocus @keyup.enter="updateFolder(folder)" @blur="updateFolder(folder)" type="text" :placeholder="folder.name" v-model="folder.name">
        <div @click="editFolder(folder)" v-else title="Click to edit folder name">
          {{ folder.name}}
        </div>
        </div>
      </drag>
      </drop>
    </div>
    </div>
    <div class="container-fluid text-center tiles">

      <div class="col-sm-12 col-md-4 col-md-offset-4 text-center p-5" v-if="pagination.total == 0" v-cloak>
        <figure>
        <i class="fa fa-folder-open-o" style="font-size: 4em"></i>
        <figcaption>
          <p class="title is-2">
          This folder is empty!
          </p>
        </figcaption>
        </figure>
      </div>

      <div class="text-center col-sm-12" v-if="loading">
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        <span class="sr-only">Loading...</span>
      </div>

      <file class="col-lg-2 col-md-3 col-sm-6"
        v-for="file in files"
        :key="file.id"
        :file="file"
        :canDrag="canEdit"
        :canDelete="canDelete"
        :panelClass="file.panelClass"
        :icon="file.icon"
        @select="editFile(file)"
        @delete="prepareToDelete(file)"
      ></file>

    </div>
  </div>

  <nav class="text-center" role="navigation" aria-label="pagination" v-if="pagination.last_page > 1" v-cloak>
    <ul class="pagination">
    <li><a @click.prevent="changePage(1)" :disabled="pagination.current_page <= 1">«</a></li>
    <li><a @click.prevent="changePage(pagination.current_page - 1)" :disabled="pagination.current_page <= 1">‹</a></li>
    <li v-for="page in pages" :key="page" :class="isCurrentPage(page) ? 'active' : ''">
      <a class="pagination-link" @click.prevent="changePage(page)">
      {{ page }}
      </a>
    </li>
    <li><a @click.prevent="changePage(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page">›</a></li>
    <li><a @click.prevent="changePage(pagination.last_page)" :disabled="pagination.current_page >= pagination.last_page">»</a></li>
    </ul>
  </nav>

  <edit-modal v-model="editingFile" @close="cancelEditing" @save="updateFile"></edit-modal>

</div>
</template>


<script>
import { Drag, Drop } from 'vue-drag-drop';
import Modal from './Modal';
import EditModal from './EditModal';
import File from './File';
import mediaApi from '../api/media';

export default {
  components: {
    Modal,
    EditModal,
    File,
    Drag,
    Drop,
  },
  directives: {
    'autofocus': {
      inserted(el) {
        el.focus();
      }
    }
  },
  props: {
    canCreate: {
      type: Boolean,
      default: false,
    },
    canDelete: {
      type: Boolean,
      default: false,
    },
    canEdit: {
      type: Boolean,
      default: false,
    },
    highlightFiles: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      folders: [],
      folderTree: [],
      files: [],
      file: {},

      tags: [],

      pagination: {},
      offset: 5,

      currentFolder: null,
      activeTab: 'image',
      isVideo: false,
      loading: false,

      formData: {},
      fileName: '',
      attachment: '',

      folderName: '',

      editingFile: {},
      deletingFile: {},

      editingFolder: {},
      deletingFolder: {},

      notification: false,
      showConfirm: false,
      showEdit: false,
      modalActive: false,
      message: '',
      errors: {},
      upFolderOver: false,
    };
  },

  watch: {
    highlightFiles(newValue) {
      this.highlight();
    },
  },

  methods: {
    highlight() {
      const highlightIds = this.highlightFiles.map(file => file.id);
      this.files.map(file => {
        const isHighlighted = highlightIds.includes(file.id);
        Vue.set(file, 'panelClass', isHighlighted ? 'success' : 'default');
        Vue.set(file, 'icon', isHighlighted ? 'check' : null);
      });
    },

    isActive(tabItem) {
      return this.activeTab === tabItem;
    },

    setActive(tabItem) {
      this.activeTab = tabItem;
    },

    isCurrentPage(page) {
      return this.pagination.current_page === page;
    },

    fetchFile(type, page) {
      this.loading = true;
      // this.files = [];
      // this.folders = [];

      mediaApi.index({
        type: type,
        page: page,
        folderId: this.currentFolder ? this.currentFolder.id : ''
      }).then(result => {
        this.loading = false;
        this.files = result.data.data;
        this.folders = result.folders.map(folder => ({ ...folder, over: false }));
        this.folderTree = result.folderTree;
        this.pagination = result.pagination;
        this.highlight();
      }).catch(error => {
        this.loading = false;
        this.showNotification(error, false);
      });

    },

    getFiles(type) {
      this.setActive(type);
      this.fetchFile(type);

      if (this.activeTab === 'video') {
        this.isVideo = true;
      } else {
        this.isVideo = false;
      }
    },

    newFile() {
      this.formData = new FormData();
      this.formData.append('name', this.fileName);
      this.formData.append('file', this.attachment);

      if (this.currentFolder) {
        this.formData.append('folder_id', this.currentFolder.id);
      }

      window.axios.post('/admin/files/add', this.formData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then(response => {
          this.resetForm();
          this.showNotification('File successfully upload!', true);
          this.fetchFile(this.activeTab);
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          this.showNotification(error.response.data.message, false);
          this.fetchFile(this.activeTab);
        });
    },

    newFolder() {
      this.formData = new FormData();
      this.formData.append('name', this.folderName);

      if (this.currentFolder) {
        this.formData.append('parent_id', this.currentFolder.id);
      }

      window.axios.post('/admin/files/folder/add', this.formData, {headers: {'Content-Type': 'multipart/form-data'}})
        .then(response => {
          this.folderName = '';
          this.showNotification('Folder successfully upload!', true);
          this.fetchFile(this.activeTab);
        })
        .catch(error => {
          this.errors = error.response.data.errors;
          this.showNotification(error.response.data.message, false);
          this.fetchFile(this.activeTab);
        });
    },

    moveIntoFolder(targetFolder, data) {
      targetFolder.over = false;
      this.upFolderOver = false;

      switch (data.type) {
        case 'folder':
          if (targetFolder.id === data.folder.id) {
            return;
          }
          this.updateFolder({
            ...data.folder,
            parent_id: targetFolder.id,
          });
          break;

        case 'file':
          const {folder, ...fileWithoutFolder} = data.file;
          this.updateFile({
            ...fileWithoutFolder,
            folder_id: targetFolder.id,
          });
          break;

        default:
          this.showNotification(`Unknown type: '${data.type}'`, false);
          break;
      }
    },

    addFile() {
      this.attachment = this.$refs.file.files[0];
    },

    prepareToDelete(file) {
      this.deletingFile = file;
      this.showConfirm = true;
    },

    prepareToDeleteFolder(folder) {
      this.deletingFolder = folder;
      this.showConfirm = true;
    },

    cancelDeleting() {
      this.deletingFile = {};
      this.deletingFolder = {};
      this.showConfirm = false;
    },

    deleteFile() {
      const config = {};
      if (this.deletingFolder.id) {
        config.url = `/admin/files/folder/delete/${this.deletingFolder.id}`;
        config.type = 'Folder';
      } else {
        config.url = `/admin/files/delete/${this.deletingFile.id}`;
        config.type = 'File';
      }
      window.axios.post(config.url)
        .then(response => {
          this.showNotification(`${config.type} successfully deleted!`, true);
          this.fetchFile(this.activeTab, this.pagination.current_page);
        })
        .catch(error => {
          this.errors = error.response.data.errors();
          this.showNotification('Something went wrong! Please try again later.', false);
          this.fetchFile(this.activeTab, this.pagination.current_page);
        });

      this.cancelDeleting();
    },

    editFolder(folder) {
      if (!this.canEdit) {
        this.$emit('selectFolder', folder);
        return;
      }
      this.editingFolder = folder;
    },

    updateFolder(folder) {
      this.editingFolder = {};

      if (folder.name.trim() === '') {
        alert('Folder name cannot be empty!');
        this.fetchFile(this.activeTab, this.pagination.current_page);
      } else {
        let formData = new FormData();
        formData.append('name', folder.name);
        if (folder.parent_id) {
          formData.append('parent_id', folder.parent_id);
        }

        axios.post('/admin/files/folder/edit/' + folder.id, formData)
          .then(response => {
            if (response.data.success === true) {
              this.showNotification('Folder successfully updated', true, () => this.updateFolder(response.data.original));
            }
          })
          .catch(error => {
            this.showNotification(error.response.data.message, false);
            for (const field in error.response.data) {
              if (error.response.data.hasOwnProperty(field)) {
                const message = error.response.data[field];
                this.showNotification(message, false);
              }
            }
          })
          .finally(() => {
            this.fetchFile(this.activeTab, this.pagination.current_page);
          });
      }
    },

    editFile(file) {
      if (!this.canEdit) {
        this.$emit('selectFile', file);
        return;
      }
      this.editingFile = {
        ...file,
        tags: file.tags.map(tag => tag.name),
        name: file.filename,
        exists: true,
      };

      this.editingFile.show = true;
    },

    cancelEditing() {
      this.editingFile = {};
      this.showEdit = false;
    },

    updateFile(file) {
      const formData = new FormData();
      formData.append('caption', file.caption);
      if (file.file) {
        formData.set("file", file.file, file.name);
      }
      if (file.tags) {
        file.tags.map(tag => {
          formData.append('tags[]', tag.name);
        });
      }
      if (file.folder && file.folder.id) {
        formData.append('folder_id', file.folder.id);
      }
      if (file.folder_id) {
        formData.append('folder_id', file.folder_id);
      }

      window.axios.post('/admin/files/edit/' + file.id, formData)
        .then(response => {
          if (response.data.success === true) {
            this.showNotification('File successfully updated', true, () => this.updateFile(response.data.original));
          }
          this.editingFile = {};
        })
        .catch(error => {
          this.showNotification(error.response.data.message, false);
          for (const field in error.response.data) {
            if (error.response.data.hasOwnProperty(field)) {
              const message = error.response.data[field];
              this.showNotification(message, false);
            }
          }
        })
        .finally(() => {
          this.fetchFile(this.activeTab, this.pagination.current_page);
        });
    },

    showNotification(text, success, undoAction = null) {
      if (success === true) {
        this.clearErrors();
      }

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
      })
    },

    showModal(file) {
      this.file = file;
      this.modalActive = true;
    },

    closeModal() {
      this.modalActive = false;
      this.file = {};
    },

    changePage(page) {
      if (page > this.pagination.last_page) {
        page = this.pagination.last_page;
      }
      this.pagination.current_page = page;
      this.fetchFile(this.activeTab, page);
    },

    resetForm() {
      this.formData = {};
      this.fileName = '';
      this.attachment = '';
    },

    anyError() {
      return Object.keys(this.errors).length > 0;
    },

    clearErrors() {
      this.errors = {};
    },

    openFolder(folder) {
      this.currentFolder = folder;
      this.fetchFile(this.activeTab);
    },

    upFolder(folder) {
      if (folder && folder.parent_id) {
        this.currentFolder = {id: folder.parent_id};
      } else {
        this.currentFolder = null;
      }
      this.fetchFile(this.activeTab);
    },

    addTag(name) {
      const newTag = {name: name};
      this.tags.push(newTag);
      this.editingFile.tags.push(newTag);
    },
  },

  mounted() {
    this.fetchFile(this.activeTab, this.pagination.current_page);
    document.getElementById('app').style.display = 'block';
    window.axios.get('/admin/files/tags').then(response => {
      this.tags = response.data;
    })
  },

  computed: {
    pages() {
      let pages = [];

      let from = this.pagination.current_page - Math.floor(this.offset / 2);

      if (from < 1) {
        from = 1;
      }

      let to = from + this.offset - 1;

      if (to > this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      while (from <= to) {
        pages.push(from);
        from++;
      }

      return pages;
    }
  },
}
</script>

<style>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .3s;
  }
  .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
    opacity: 0;
  }
  a {
    cursor: pointer;
  }
  .new-btn {
    margin-bottom: 1em;
  }
  .delete-btn {
    padding: 1px 5px;
    position: absolute;
    right: 5px;
    top: 5px;
    text-indent: 100%;
    overflow: hidden;
    width: 1.5em;
    height: 1.5em;
    border-radius: 50%;
    background-color: rgba(0,0,0,.25);
    border-color: rgba(0,0,0,0);
  }
  .delete-btn::after {
    content: '×';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-indent: 0;
    font-size: 1.5em;
  }
  .p-5 {
    padding: 3em;
  }
  .panel {
    height: calc(100% - 20px);
    position: relative;
  }
  .panel::after {
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
  .is-dropping::after {
    opacity: 1;
  }
  .toast-action {
    color: white !important;
    opacity: .75;
    font-size: .9em !important;
  }
  .toast-action:hover {
    opacity: 1;
    text-decoration: none !important;
  }
  .toast-action--dismiss {
    font-size: 1.5em !important;
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
