<script setup>
  const emit = defineEmits(['close']);

  const props = defineProps({
    persistent: {
      type: Boolean,
      value: false,
    },
  });

  const emitClose = () => {
    if (props.persistent) return;
    emit('close');
  };
</script>

<template>
  <Teleport to="#app">
    <div class="inset-0 fixed overflow-y-auto flex justify-center items-center" v-bind="$attrs" >
      <div @click.prevent="emitClose" class="fixed inset-0 w-full flex min-h-screen justify-center items-center bg-gray-700/50 z-10"></div>
      <div class="flex">
        <div class="mx-auto sm:min-w-[368px] md:max-w-[768px] bg-white box-shadow-lg rounded-lg min-w-2xl z-20 inset-0">
          <div class="px-5 py-4">
            <slot name="title" />
          </div>
          <div class="px-4">
            <slot name="body" />
          </div>
          <div class="flex justify-end mt-5  px-5 py-4">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>
