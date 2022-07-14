<script setup>
import { ref, computed } from "vue";
import { useRouter } from "vue-router";
import { isValidPhoneNumber, parsePhoneNumber } from "libphonenumber-js";
import { register } from "../api/api";

const firstName = ref("");
const lastName = ref("");
const phoneNumber = ref("");
const password = ref("");

const formError = ref("");

const router = useRouter();

const isFormDataValid = computed(() => {
  return (
    phoneNumber &&
    phoneNumber.value.trim() &&
    password &&
    firstName &&
    firstName.value.trim() &&
    lastName &&
    lastName.value.trim()
  );
});

async function doRegister() {
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
    await register(
      firstName.value.trim(),
      lastName.value.trim(),
      parsePhoneNumber(phoneNumber.value.trim(), "UG").format("E.164"),
      password.value
    );
    // router.push({
    //   name: "profile",
    //   params: { phoneNumber: phoneNumber.value.trim() },
    // });
    router.push({ name: "login", params: { isFromRegistration: true } });
  } catch (error) {
    formError.value = error.message;
  }
}
</script>

<template>
  <div id="form-container">
    <form>
      <div id="formError" v-if="formError">{{ formError }}</div>
      <input
        type="text"
        size="1"
        placeholder="First Name"
        v-model="firstName"
      />
      <input type="text" size="1" placeholder="Last Name" v-model="lastName" />
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
      <button @click.prevent="doRegister" type="submit">Register</button>
      <p id="login-option">
        Already have an account? <router-link to="/login">Log in</router-link>
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

#login-option {
  align-self: flex-end;
  font-size: small;
}

#formError {
  color: white;
  background-color: red;
  padding: 10px;
}
</style>
