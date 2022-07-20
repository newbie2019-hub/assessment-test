<template>
  <div v-if="links.length > 3">
    <div class="flex flex-wrap -mb-1 mt-4" :class="{'justify-end' : right}">
      <template v-for="(link, key) in links">
        <div v-if="link.url === null" :key="key" class="mb-1 px-4 py-3 text-gray-400 text-sm leading-4 border" v-html="link.label" />
        <Link
          v-else
          :key="`link-${key}`"
          class="mb-1 px-4 py-3 focus:text-indigo-500 text-sm leading-4 hover:bg-white border focus:border-indigo-500"
          :class="{ 'border-2 border-blue-500': link.active }"
          :href="link.url"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>

<script>
  import { Link } from '@inertiajs/inertia-vue3';
  export default {
    components: {
      Link,
    },
    props: {
      links: Array,
      right: {
        type: Boolean,
        value: false
      },
    },
  };
</script>
