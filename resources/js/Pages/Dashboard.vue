<script setup>
  import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
  import { ref, watch, onMounted, reactive } from 'vue';
  import { Head } from '@inertiajs/inertia-vue3';
  import Pagination from '@/Shared/Pagination.vue';
  import Button from '@/Components/Button/Button.vue';
  import Modal from '@/Components/Modal/Modal.vue';
  import vSelect from 'vue-select';
  import { debounce } from 'lodash';
  import axios from 'axios';

  import { useForm } from '@inertiajs/inertia-vue3';
  import { Inertia } from '@inertiajs/inertia';

  let modalUsers = ref([]);
  let viewedRelationship = ref([]);

  let form = useForm({
    relationship_id: null,
    user_id: null,
    related_user_id: null,
  });

  defineProps({
    users: Object,
    relationships: Object,
    errors: Object,
  });

  onMounted(async () => {
    await fetchUsers();
  });

  const fetchUsers = debounce(async (search, loading) => {
    const { data } = await axios.post('/users', { search });
    modalUsers.value = data;
  }, 200);

  const saveRelationship = () => {
    form.post('/relationships', {
      preserveScroll: true,
      onSuccess: () => {
        form.reset();
        closeModal();
      },
    });
  };

  const unlinkRelationship = (id) => {
    Inertia.delete(`/relationships/${id}`,{
      _method: 'delete',
      onSuccess: () => {
        form.reset();
        toggleViewModal()
      }
    });
  };

  let isModalOpen = ref(false);
  let isModalViewOpen = ref(false);

  const openModal = (id) => {
    isModalOpen.value = true;
    form.related_user_id = id;
  };

  const closeModal = () => {
    isModalOpen.value = !isModalOpen.value;
  };

  const toggleViewModal = () => {
    isModalViewOpen.value = !isModalViewOpen.value;
  };

  watch(isModalOpen, () => {
    document.documentElement.classList.toggle('overflow-y-hidden');
  });
</script>

<template>
  <Head title="Dashboard" />
  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div v-if="$page.props.flash.success" class="rounded-md bg-green-600 p-4 max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-white fill-transparent" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div class="ml-3 flex-1 md:flex md:justify-between">
          <p class="text-sm text-white">{{ $page.props.flash.success }}</p>
          <p class="mt-3 text-sm md:mt-0 md:ml-6">
            <a @click.prevent="$page.props.flash.success = ''" href="#" class="whitespace-nowrap font-medium text-white [&>*]:hover:translate-x-4">Close <span aria-hidden="true">&rarr;</span></a>
          </p>
        </div>
      </div>
    </div>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white border-b border-gray-200 rounded-lg pb-6">
        <div class="p-6 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr class="[&>*]:uppercase font-medium text-xs text-gray-500">
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left sm:pl-6">ID</th>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left sm:pl-6 whitespace-nowrap">First Name</th>
                <th scope="col" class="px-3 py-3.5 text-left whitespace-nowrap">Last Name</th>
                <th scope="col" class="px-3 py-3.5 text-left whitespace-nowrap">Contact Number</th>
                <th scope="col" class="px-3 py-3.5 text-left">Gender</th>
                <th scope="col" class="px-3 py-3.5 text-left">Email</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6 text-left">Relationships</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-for="(user, i) in users.data" :key="i">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ user.id }}</td>
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ user.first_name }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ user.last_name }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ user.contact_number }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900 capitalize">{{ user.gender }}</td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">{{ user.email }}</td>
                <td v-if="$attrs.auth.user.isAdmin" class="relative whitespace-nowrap py-4 pl-3 pr-4 text-sm sm:pr-6">
                  <Button
                    text
                    size="sm"
                    @click.prevent="
                      toggleViewModal();
                      viewedRelationship = user;
                    "
                    >View</Button
                  >
                  <Button text size="sm" @click.prevent="openModal(user.id)">Add</Button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <pagination :links="users.links" right />
      </div>

      <!-- Adding of relationship -->
      <Modal v-if="isModalOpen" @close="closeModal">
        <template v-slot:title>
          <p class="font-bold text-xl">Link Relationship</p>
          <p class="text-sm text-gray-600">Please select a user and relation to make a link.</p>
        </template>
        <template v-slot:body>
          <label for="">Relationship Type</label>
          <v-select v-model="form.relationship_id" label="type" :options="relationships" :reduce="(type) => type.id">
            <template v-slot:no-options="{ search, searching }">
              <template v-if="searching">
                No results found for <em>{{ search }}</em
                >.
              </template>
              <em v-else style="opacity: 0.5">Start typing to search for a country.</em>
            </template>
          </v-select>
          <p v-if="errors.user_id" class="text-sm text-red-600">{{ errors.relationship_id }}</p>
          <label for="" class="mt-2">Link To</label>
          <v-select v-model="form.user_id" @search="fetchUsers" label="full_name" :options="modalUsers" :reduce="(user) => user.id">
            <template v-slot:no-options="{ search, searching }">
              <template v-if="searching">
                <p class="text-sm">
                  Empty results for<em>{{ search }}</em
                  >.
                </p>
              </template>
              <em v-else style="opacity: 0.5">Start typing to search for a country.</em>
            </template>
          </v-select>
          <p v-if="errors.user_id" class="text-sm text-red-600">{{ errors.user_id }}</p>
          <p v-if="errors.message" class="text-sm text-red-600">{{ errors.message }}</p>
        </template>
        <template v-slot:footer>
          <Button @click.prevent="closeModal" text size="sm" color="gray">Close</Button>
          <Button @click.prevent="saveRelationship" text size="sm" color="success">Save</Button>
        </template>
      </Modal>

      <!-- Viewing and unlink -->
      <Modal v-if="isModalViewOpen" @close="toggleViewModal">
        <template v-slot:title>
          <p class="font-bold text-xl">User Relationship</p>
          <p class="text-sm text-gray-600">You may unlink a relationship if not needed.</p>
        </template>
        <template v-slot:body>
          <ul role="list" class="divide-y divide-gray-200">
            <li v-for="user in viewedRelationship.relationships" :key="user.id" class="py-2 flex">
              <div class="ml-3 flex justify-between w-full items-center">
                <div class="flex flex-col">
                  <p class="text-sm text-gray-500 uppercase">{{ user.type.type }}</p>
                  <p class="font-medium text-gray-900">{{ user.user_related.last_name + ', ' + user.user_related.first_name }}</p>
                  <p class="text-xs">{{ user.user_related.email }}</p>
                </div>
                <div>
                  <Button @click.prevent="unlinkRelationship(user.id)" size="sm" text>Unlink</Button>
                </div>
              </div>
            </li>
          </ul>

          <ul role="list" class="divide-y divide-gray-200" :class="{'mt-6' : viewedRelationship.relationship}">
            <li v-for="user in viewedRelationship.related_users" :key="user.id" class="py-2 flex">
              <div class="ml-3 flex justify-between w-full items-center">
                <div class="flex flex-col">
                  <p class="text-sm text-gray-500 uppercase">{{ user.type.type }} of</p>
                  <p class="font-medium text-gray-900">{{ user.related_user.last_name + ', ' + user.related_user.first_name }}</p>
                  <p class="text-xs">{{ user.related_user.email }}</p>
                </div>
                <div>
                  <Button @click.prevent="unlinkRelationship(user.id)" size="sm" text>Unlink</Button>
                </div>
              </div>
            </li>
          </ul>
        </template>
        <template v-slot:footer>
          <Button @click.prevent="toggleViewModal" text size="sm" color="gray">Close</Button>
        </template>
      </Modal>
    </div>
  </BreezeAuthenticatedLayout>
</template>
