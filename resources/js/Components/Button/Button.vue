<script setup>
  import { ref } from 'vue';

  let cssClass = ref('');

  const props = defineProps({
    type: {
      type: String,
      default: 'submit',
    },
    color: {
      type: String,
      default: 'primary',
    },
    text: {
      type: Boolean,
      default: false,
    },
    size: {
      type: String,
      default: 'default',
    },
  });

  const setButtonProperty = () => {
    if (props.text) {
      textButton();
    } else {
      coloredButton();
    }
  };

  const textButton = () => {
    switch (props.color) {
      case 'gray':
        cssClass = 'text-neutral-700 hover:bg-gray-200 hover:text-gray-900 ring-gray-500';
        break;
      case 'warning':
        cssClass = 'text-orange-500 hover:bg-orange-50 ring-orange-500';
        break;
      case 'success':
        cssClass = 'text-green-500 hover:bg-green-100 ring-green-500';
        break;
      case 'error':
        cssClass = 'text-red-500 hover:bg-red-50 ring-red-500';
        break;
      default:
        cssClass = 'text-blue-600 hover:bg-blue-100 ring-blue-500';
    }
  };

  const coloredButton = () => {
    switch (props.color) {
      case 'gray':
        cssClass = 'bg-gray-500 hover:bg-gray-400 ring-gray-500';
        break;
      case 'warning':
        cssClass = 'bg-orange-500 hover:bg-orange-400 ring-orange-500';
        break;
      case 'success':
        cssClass = 'bg-green-600 hover:bg-green-500 ring-green-600';
        break;
      case 'error':
        cssClass = 'bg-red-500 hover:bg-red-400 ring-red-500';
        break;
      default:
        cssClass = 'bg-blue-600 hover:bg-blue-500 ring-blue-500';
    }
  };

  const buttonSize = () => {
    switch (props.size) {
      case 'lg':
        cssClass += ' px-7 py-3 text-md';
        break;
      case 'sm':
        cssClass += ' px-4 py-2 text-xs';
        break;
      default:
        cssClass += ' px-4 py-2';
        break;
    }
  };

  setButtonProperty();
  buttonSize();
</script>

<template>
  <button :type="type" class="duration-200 ease-in-out font-medium inline-flex rounded uppercase focus:ring-2 ring-offset-2 text-white ml-2 tracking-wider" :class="cssClass">
    <slot />
  </button>
</template>
