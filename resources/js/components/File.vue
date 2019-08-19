<template>
  <drag
    :draggable="canDrag"
    :class="file.type == 'image' ? 'is-image' : 'is-file'"
    :transfer-data="{type: 'file', file: file}"
  >
    <div :class="['panel', `panel-${panelClass}`]">
      <div class="panel-image">
        <button v-if="canDelete" class="btn btn-danger btn-sm delete-btn" title="Delete" @click.prevent="$emit('delete')">
          <i class="fa fa-trash"></i> Delete
        </button>
        <i v-if="computedIcon" @click="$emit('select')" :class="['icon', 'fa', `fa-${computedIcon}`]"></i>
        <a class="" v-if="file.type == 'image'" @click="$emit('select')">
          <img class="img-responsive" width="100%" :src="'/images/medium/' + file.filename" :alt="file.filename" draggable="false">
        </a>
      </div>
      <div :class="[panelClass === 'default' ? 'panel-footer' : 'panel-heading', 'text-center']">
        <div v-if="file.type == 'pdf'" class="document_block">
        <a class="text-center center-block" style="font-size: 4em" @click.prevent="$emit('select')">
        </a>
        <a class="btn btn-sm btn-default" :href="'/media/' + file.type + '/' + file.filename" target="_blank">
          <i class="fa fa-download" aria-hidden="true"></i>
          &nbsp;Download
        </a>
        </div>

        <div v-if="file.type == 'misc'" class="document_block">
        <a class="text-center center-block" style="font-size: 4em" @click.prevent="$emit('select')">
          <i class="fa fa-file-o"></i>
        </a>
        <a class="btn btn-sm btn-default" :href="'/media/' + file.type + '/' + file.filename" target="_blank">
          <i class="fa fa-download" aria-hidden="true"></i>
          &nbsp;Download
        </a>
        </div>
        <div @click.prevent="$emit('select')">
          {{ file.caption || file.filename }}
        </div>
        <slot></slot>
      </div>
    </div>
  </drag>
</template>

<script>
import { Drag } from 'vue-drag-drop';
export default {
  components: {
    Drag,
  },
  props: {
    file: {
      type: Object,
    },
    canDrag: {
      type: Boolean,
      default: false,
    },
    canDelete: {
      type: Boolean,
      default: false,
    },
    panelClass: {
      type: String,
      default: 'default',
    },
    icon: {
      type: String,
      default: null,
    },
  },
  computed: {
    computedIcon() {
      return this.icon
        || (this.file.type === 'pdf' ? 'file-pdf-o' : null)
        || (this.file.type === 'misc' ? 'file-o' : null);
    }
  }
}
</script>

<style scoped>
  .panel {
    overflow: hidden;
    height: 100%;
    position: relative;
    margin-bottom: 0;
  }
  .panel-heading,
  .panel-footer {
    height: 100%;
  }
  .icon {
    display: flex;
    position: absolute;
    justify-content: center;
    align-items: center;
  }
  .is-file .icon {
    font-size: 4em;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
  }
  .is-image .icon {
    top: 5px;
    right: 5px;
    border-radius: 50%;
    background-color: rgba(60, 118, 61, 0.75);
    color: white;
    padding: 1em;
  }
  .panel-image {
    position: relative;
  }
  .is-file .panel-image {
    min-height: 8em;
  }
</style>