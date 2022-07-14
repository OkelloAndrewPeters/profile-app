<script setup>
import { ref, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { getProfileData } from "../api/api";
import { USER_TOKEN_KEY, USER_PHONE_KEY } from "../constants";

const firstName = ref("");
const lastName = ref("");
const phoneNumber = ref("");

const isLoading = ref(true);
const errorMessage = ref("");

const router = useRouter();
const route = useRoute();

const checkCredentials = () => {
  const token = JSON.parse(localStorage.getItem(USER_TOKEN_KEY));
  if (!token) {
    router.push({ name: "login" });
  }
};

const loadProfile = async () => {
  errorMessage.value = "";
  checkCredentials();
  try {
    const data = await getProfileData(route.params.phoneNumber);
    firstName.value = data["first_name"];
    lastName.value = data["last_name"];
    phoneNumber.value = data["phone_number"];
  } catch (error) {
    errorMessage.value = error.message;
  } finally {
    isLoading.value = false;
  }
};

onMounted(() => {
  loadProfile();
});
</script>

<template>
  <div id="profile-container">
    <div id="profile">
      <div v-if="isLoading">Loading...</div>
      <div id="error" v-else-if="errorMessage">{{ errorMessage }}</div>
      <div id="profile-data" v-if="!isLoading && !errorMessage">
        <div>
          <img
            id="profile-pic"
            :src="`https://robohash.org/${firstName}${lastName}.png`"
            alt="profile"
          />
        </div>
        <div id="name">{{ firstName }} {{ lastName }}</div>
        <div id="phone-number">{{ phoneNumber }}</div>
      </div>
      <button @click="router.push({ name: 'login' })" v-if="errorMessage">
        Log in
      </button>
    </div>
  </div>
</template>

<style scoped>
#profile-container {
  display: flex;
  justify-content: center;
}

#profile-pic {
    width: 120px;
    height: 120px;
}

#profile {
  max-width: 500px;
  padding: 50px;
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
  background-color: white;
  margin-top: 50px;
}

#profile-data {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 20px;
}

#name {
  letter-spacing: 2px;
}

#phone-number {
  padding: 10px;
  background-color: green;
  color: white;
}

#error {
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  background-color: red;
  padding: 10px;
}
</style>
