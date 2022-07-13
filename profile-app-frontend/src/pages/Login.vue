<template>
  <div>
    <form>
      <input type="tel" size="1" placeholder="Phone Number" />
      <input type="password" size="1" placeholder="Password" />
      <button type="submit">Login</button>
			<p>
				Don't have an account? <router-link to="/register">Create account</router-link>
			</p>
    </form>
  </div>
</template>

<style scoped>
div {
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
</style>
