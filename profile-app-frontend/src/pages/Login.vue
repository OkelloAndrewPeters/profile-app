<script setup>
import { ref, onMounted, computed } from "vue";
import { isValidPhoneNumber, parsePhoneNumber } from "libphonenumber-js";
import { login } from "../api/api";
import { useRouter } from "vue-router";

const { isFromRegistration } = defineProps({
  isFromRegistration: {
    type: Boolean,
    default: false,
  },
});

const isRegisterSuccessBannerOpen = ref(true);

const closeBanner = (timeout) => {
  setTimeout(() => {
    isRegisterSuccessBannerOpen.value = false;
  }, timeout);
};

onMounted(() => {
  if (isFromRegistration) {
    closeBanner(3500);
  }
});

const phoneNumber = ref("");
const password = ref("");

const formError = ref("");

const router = useRouter();

const isFormDataValid = computed(() => {
  return phoneNumber && phoneNumber.value.trim() && password;
});

async function doLogin() {
  formError.value = "";
  if (!isFormDataValid) {
    formError.value = "Missing information";
    return;
  }
  if (!isValidPhoneNumber(phoneNumber.value.trim(), "UG")) {
    formError.value = "Invalid phone number";
    return;
  }
  try {
    await login(
      parsePhoneNumber(phoneNumber.value.trim(), "UG").format("E.164"),
      password.value
    );
    router.push({
      name: "profile",
      params: { phoneNumber: phoneNumber.value.trim() },
    });
  } catch (error) {
    formError.value = error.message;
  }
}
</script>

<template>
  <div id="form-container">
    <form>
      <div
        id="register-success-banner"
        v-if="isFromRegistration && isRegisterSuccessBannerOpen"
      >
        Registration successful
      </div>
      <div id="formError" v-if="formError">{{ formError }}</div>
      <input
        type="tel"
        size="1"
        placeholder="Phone Number"
        v-model="phoneNumber"
      />
      <input
        type="password"
        size="1"
        placeholder="Password"
        v-model="password"
      />
      <button @click.prevent="doLogin" type="submit">Login</button>
      <p>
        Don't have an account?
        <router-link to="/register">Create account</router-link>
      </p>
    </form>
  </div>
</template>

<style scoped>
#form-container {
  display: flex;
  justify-content: center;
  padding: 20px;
}

form {
  max-width: 500px;
  padding: 50px;
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 20px;
  background-color: white;
  margin-top: 50px;
}

input {
  height: 35px;
  padding: 5px;
}

button {
  align-self: flex-end;
  border: none;
  padding: 8px 30px;
  border-radius: 5px;
  background-color: #8e44ad;
  color: white;
}

p {
  align-self: flex-end;
  font-size: small;
}

#register-success-banner {
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  background-color: green;
  padding: 10px;
}

#formError {
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  background-color: red;
  padding: 10px;
}
</style>
