<template>
  <app-layout title="Dashboard | Photos">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Photos</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- All posts goes here -->
        <h1 class="text-2xl">Photos</h1>
        <a
          class="px-4 bg-sky-900 text-white rounded-md"
          :href="route('admin.photos.create')"
          >Create</a
        >
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <div
                class="
                  shadow
                  overflow-hidden
                  border-b border-gray-200
                  sm:rounded-lg
                "
              >
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th
                        scope="col"
                        class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                      >
                        ID
                      </th>
                      <th
                        scope="col"
                        class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                      >
                        Photos
                      </th>
                      <th
                        scope="col"
                        class="
                          px-6
                          py-3
                          text-left text-xs
                          font-medium
                          text-gray-500
                          uppercase
                          tracking-wider
                        "
                      >
                        Description
                      </th>
                      <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Edit</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="photo in photos" :key="photo.id">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ photo.id }}</div>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="flex-shrink-0 h-10 w-10">
                            <img
                              class="h-10 w-10 rounded-full"
                              :src="photo.path"
                              alt
                            />
                          </div>
                        </div>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                          {{ photo.description.slice(0, 100) + "..." }}
                        </div>
                      </td>
                      <!-- ACTIONS -->
                      <td
                        class="
                          px-6
                          py-4
                          whitespace-nowrap
                          text-right text-sm
                          font-medium
                        "
                      >
                        <a
                          href="#"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          View
                        </a>
                        <a
                          :href="route('admin.photos.edit', photo.id)"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          Edit
                        </a>
                        <danger-button @click="delete_photo(photo)">
                          Delete
                        </danger-button>
                      </td>
                    </tr>
                    <dialog-modal :show="data.show_modal">
                      <template #title>
                        Photo {{ data.photo.description.slice(0, 20) + "..." }}
                      </template>
                      <template #content>
                        Are you sure you want to delete this photo?
                      </template>
                      <template #footer>
                        <button @click="closeModal" class="px-4 py-2">
                          Close
                        </button>
                        <form @submit.prevent="deleting_photo(data.photo.id)">
                          <danger-button type="submit"
                            >Yes, I am sure!</danger-button
                          >
                        </form>
                      </template>
                    </dialog-modal>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>
<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import DialogModal from "@/Components/DialogModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
export default defineComponent({
  components: {
    AppLayout,
    DialogModal,
    DangerButton,
  },
  props: {
    photos: Array,
  },
  // / 1. add the setup function
  setup() {
    // 2. declare a form variable and assign to it the Inertia useForm() helper function
    const form = useForm({
      // 3. override the form method to make a DELETE request
      _method: "DELETE",
    });
    // 4. define a reactive object with show_modal and photo property
    // this will be used to figure out when to show the modal and the selected post values
    const data = ref({
      show_modal: false,
      photo: {
        id: null,
        path: null,
        description: null,
      },
    });

    // 5. define the delete_photo function and update the values of the show_modal and photo properties
    // of the reactive object defined above. This method is called by the delete button and will record the details
    // of the selected post
    const delete_photo = (photo) => {
      //console.log(photo);
      // console.log(photo.id, photo.path, photo.description);
      data.value = {
        photo: {
          id: photo.id,
          path: photo.path,
          description: photo.description,
        },
        show_modal: true,
      };
      // console.log(data.value.photo);
    };
    // 6. define the method that will be called when our delete form is submitted
    // the form will be created next
    const deleting_photo = (id) => {
      form.post(route("admin.photos.delete", id));
      closeModal();
    };
    // 7. delare a method to close the modal by setting the show_modal to false
    const closeModal = () => {
      data.value.show_modal = false;
    };
    // 8. remember to return from the setup function the all variables and methods that you want to expose
    // to the template.
    return { form, data, closeModal, delete_photo, deleting_photo };
  },
});
</script>