import axios from "axios";
import { isValidPhoneNumber, parsePhoneNumber } from "libphonenumber-js";
import { USER_TOKEN_KEY, USER_PHONE_KEY } from "../constants";

// change 8080 to correct server port
const BASE_URL = "http://localhost:8080";
const LOGIN_ENDPOINT = "/login";
const REGISTER_ENDPOINT = "/register";
const PROFILE_DATA_ENDPOINT = "/profile-data";

const transformAxiosError = (error) => {
  if (error.response) {
    throw new Error(error.response.data.error || error.response.data);
  } else {
    throw new Error("Something went wrong!");
  }
};

const login = async (phoneNumber, password) => {
  if (!phoneNumber || !phoneNumber.trim() || !password) {
    throw new Error("Missing information");
  }

  try {
    const {
      data: { token },
    } = await axios.post(`${BASE_URL}${LOGIN_ENDPOINT}`, {
      phoneNumber: phoneNumber.trim(),
      password,
    });
    localStorage.setItem(USER_TOKEN_KEY, JSON.stringify(token));
    localStorage.setItem(USER_PHONE_KEY, JSON.stringify(phoneNumber.trim()));
  } catch (error) {
    throw transformAxiosError(error);
  }
};

const register = async (firstName, lastName, phoneNumber, password) => {
  const isParametersValid =
    phoneNumber &&
    phoneNumber.trim() &&
    password &&
    firstName &&
    firstName.trim() &&
    lastName &&
    lastName.trim();
  if (!isParametersValid) {
    throw new Error("Missing information");
  }

  try {
    await axios.post(`${BASE_URL}${REGISTER_ENDPOINT}`, {
      firstName,
      lastName,
      phoneNumber,
      password,
    });
  } catch (error) {
    throw transformAxiosError(error);
  }
};

const getProfileData = async (phoneNumber) => {
  const token = JSON.parse(localStorage.getItem(USER_TOKEN_KEY));
  if (!phoneNumber || !phoneNumber.trim()) {
    throw new Error("phone number required");
  }
  if (!isValidPhoneNumber(phoneNumber.trim(), "UG")) {
    throw new Error("Invalid phone number");
  }
  if (!token) {
    throw new Error("No token provided");
  }
  try {
    const { data } = await axios.post(`${BASE_URL}${PROFILE_DATA_ENDPOINT}`, {
      token,
      phoneNumber: parsePhoneNumber(phoneNumber.trim(), "UG").format("E.164"),
    });

    return data;
  } catch (error) {
    throw transformAxiosError(error);
  }
};

export { login, register, getProfileData };
