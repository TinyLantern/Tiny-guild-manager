<template>
    <div class="upload-section">
      <q-uploader
        :label="label"
        filled
        dense
        :max-files="1"
        :accept="accept"
        @added="handleFileUpload"
      />
      <div v-if="previewUrl" class="preview">
        <img :src="previewUrl" alt="Preview" />
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const props = defineProps({
    label: {
      type: String,
      default: 'Upload File',
    },
    accept: {
      type: String,
      default: 'image/*',
    },
  });
  
  const emit = defineEmits(['file-uploaded']);
  
  const previewUrl = ref(null);
  
  const handleFileUpload = (files) => {
    if (files && files.length > 0) {
      const file = files[0];
      if (file instanceof Blob || file instanceof File) {
        emit('file-uploaded', file);
  
        const reader = new FileReader();
        reader.onload = () => {
          previewUrl.value = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        console.error('Uploaded file is not a valid Blob or File');
      }
    } else {
      emit('file-uploaded', null);
      previewUrl.value = null;
    }
  };
  </script>
  
  <style lang="scss">
  .upload-section {
    padding-bottom: 27px;
    width: 100%;
  }

  .q-uploader{
    width: 100%;
  }

  .q-uploader__header{
    background-color: var(--custom-purple);
  }
  
  .preview img {
    width: 100%;
    height: auto;
    border-radius: 4px;
    margin-top: 1rem;
  }
  </style>