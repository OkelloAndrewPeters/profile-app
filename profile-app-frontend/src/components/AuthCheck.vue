<script setup>
import { USER_TOKEN_KEY, USER_PHONE_KEY } from "../constants";
import { useRouter } from "vue-router";

const router = useRouter();

if (
  localStorage.getItem(USER_TOKEN_KEY && localStorage.getItem(USER_PHONE_KEY))
) {
  router.push({
    name: "profile",
    replace: true,
    params: { phoneNumber: JSON.parse(localStorage.getItem(USER_PHONE_KEY)) },
  });
} else {
  router.push({ name: "login", replace: true });
}
</script>

<template></template>
