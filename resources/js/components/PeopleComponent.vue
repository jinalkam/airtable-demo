<template>
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <h1 class="text-5xl font-bold text-blue-500 mb-20">My Team</h1>
    <div class="grid grid-cols-2 gap-20">
      <div>
        <h2 class="text-xl mb-5">Add new team member</h2>
        <ValidationObserver v-slot="{ handleSubmit }">
          <form
            class="space-y-5"
            @submit.stop.prevent="handleSubmit(onSubmit)"
            enctype="multipart/form-data"
          >
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700"
                >Name</label
              >
              <div class="mt-1">
                <ValidationProvider
                  name="Name"
                  rules="required"
                  v-slot="{ errors }"
                >
                  <input
                    v-model="form.name"
                    type="text"
                    name="fullname"
                    id="fullname"
                    class="form-control shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md"
                    :class="{ 'error-input': errors[0] }"
                    placeholder="Calvin Hawkins"
                  />
                  <span :class="{ 'error-message': errors[0] }">{{
                    errors[0]
                  }}</span>
                </ValidationProvider>
              </div>
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700"
                >Email</label
              >
              <div class="mt-1">
                <ValidationProvider
                  name="E-mail"
                  rules="required|email"
                  v-slot="{ errors }"
                >
                  <input
                    v-model="form.email"
                    type="text"
                    name="email"
                    id="email"
                    class="form-control shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 p-3 border rounded-md"
                    :class="{ 'error-input': errors[0] }"
                    placeholder="you@example.com"
                  />
                  <span :class="{ 'error-message': errors[0] }">{{
                    errors[0]
                  }}</span>
                </ValidationProvider>
              </div>
            </div>
            <div>
              <label
                for="photo"
                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
              >
                Photo
              </label>
              <div class="mt-1">
                <ValidationProvider
                  name="photo"
                  rules="mimes:image/jpeg|size:102400"
                  v-slot="{ errors, validate }"
                  ref="provider"
                >
                  <div
                    class="w-full flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md drag-area"
                    @dragover="dragover"
                    @dragleave="dragleave"
                    @drop="drop"
                  >
                    <div class="space-y-1 text-center">
                      <div class="mt-4" v-if="form.photo">
                        <img
                          :src="showImage"
                          alt=""
                          class="mx-auto h-12 w-12 text-gray-400"
                        />
                      </div>
                      <svg
                        v-else
                        class="mx-auto h-12 w-12 text-gray-400"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 48 48"
                        aria-hidden="true"
                      >
                        <path
                          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                          stroke-width="2"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>

                      <div class="flex text-sm text-gray-600">
                        <label
                          for="file-upload"
                          class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500"
                        >
                          <span>Upload a file</span>

                          <input
                            v-on:change="onImageChange"
                            id="file-upload"
                            name="file-upload"
                            type="file"
                            class="sr-only"
                            ref="file"
                            accept="image/jpeg"
                          />
                        </label>
                        <p class="pl-1">or drag and drop</p>
                      </div>
                      <p class="text-xs text-gray-500">JPG up to 100MB</p>
                    </div>
                  </div>
                  <span :class="{ 'error-message': errors[0] }">{{
                    errors[0]
                  }}</span>
                </ValidationProvider>
              </div>
            </div>
            <button
              type="submit"
              :disabled="isSubmittingForm"
              class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              :class="isSubmittingForm ? 'bg-indigo-100' : 'bg-indigo-600 hover:bg-indigo-700'"
            >
              <i v-if="isSubmittingForm" class="fa fa-spinner fa-spin mr-3"></i>
              <span>Submit</span>
            </button>
          </form>
        </ValidationObserver>
      </div>
      <div>
        <ul class="divide-y divide-gray-200">
          <li class="py-4 flex" v-for="(value, key) in usersList" :key="key">
            <img
              v-if="value.fields.Photo"
              class="h-10 w-10 rounded-full"
              :src="value.fields.Photo[0].url"
              alt=""
            />
            <img
              v-else
              class="h-10 w-10 rounded-full"
              src="defaultUser.svg"
              alt=""
            />
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">
                {{ value.fields.Name }}
              </p>
              <p class="text-sm text-gray-500">{{ value.fields.Email }}</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { extend, localize } from "vee-validate";
import en from "vee-validate/dist/locale/en.json";
import * as rules from "vee-validate/dist/rules";

localize({ en });
Object.keys(rules).forEach((rule) => {
  extend(rule, rules[rule]);
});

extend("mimes", {
  message: "The photo field must have a valid file type JPEG/JPG",
});

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },

  props: {
    users: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      isSubmittingForm: false,
      showImage: "",
      usersList: [],
      form: {
        name: null,
        email: null,
        photo: "",
      },
    };
  },

  mounted() {},
  watch: {
    users: {
      immediate: true,
      handler(data) {
        this.usersList = data;
      },
    },
  },

  methods: {
    dragover(e) {
      e.preventDefault();
      // Add some visual fluff to show the user can drop its files
        this.darkenBg(e.currentTarget);
   
    },

    dragleave(e) {
      e.currentTarget.classList.remove("bg-gray-100");
    },

    drop(e) {
      e.preventDefault();
      let file = e.dataTransfer.files[0];
      this.previewImage(file);
    },

    onImageChange(e) {
      let file = e.target.files[0];
      this.previewImage(file);
    },

    darkenBg(node) {
      node.classList.add("bg-gray-100");
    },

    previewImage(file) {
      let reader = new FileReader();
      reader.onload = (f) => {
        // f.target.result contains the base64 encoding of the image
        this.showImage = f.target.result;
      };
      reader.readAsDataURL(file);
      this.form.photo = file;
      const valid = this.$refs.provider.validate(file);

      // darken bg
      const node = document.querySelector('.drag-area')
      this.darkenBg(node)
    },

    async onSubmit() {
      try {
      this.isSubmittingForm = true;
      let formData = new FormData();
      const config = {
        headers: { "content-type": "multipart/form-data" },
      };
      formData.append("photo", this.form.photo);
      formData.append("name", this.form.name);
      formData.append("email", this.form.email);

      const { data } = await window.axios.post("/api/user", formData, config);
      this.usersList.push(data.user);
      
      } catch(e) {
        console.error(e)
      } finally {
        this.isSubmittingForm = false;
      }
    },
  },
};
</script>

<style lang="css" scoped>
.error-input {
  border-color: #dc3545;
}
span.error-message {
  width: 100%;
  margin-top: 0.25rem;
  font-size: 80%;
  color: #dc3545;
}
</style>