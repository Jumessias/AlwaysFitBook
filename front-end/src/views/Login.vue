
<template>
  <div class="text-start login">
    <form class="form-signin custom-form" v-if="!is_register">
      <div class="text-center w-100">
        <img class="mb-4" src="../assets/logo.png" alt="" height="100">
      </div>
      <h1 class="h3 mb-3 font-weight-normal text-center title">Fazer Login</h1>
      <label for="inputEmail" class="sr-only">Endereço de email:</label>
      <input type="email" v-model="login.email" id="inputEmail" class="form-control mb-3" placeholder="Seu email..."
        required autofocus>
      <label for="inputPassword" class="sr-only">Senha:</label>
      <input type="password" v-model="login.password" id="inputPassword" class="form-control" placeholder="Senha..."
        required>

      <div class="text-center w-100 mt-3">
        <button class="btn btn-lg btn-primary btn-block w-100 mb-2 custom1" type="button"
          @click.prevent="SendLogin()">Login</button>
        <a href="" @click.prevent="is_register = true">Fazer Cadastro</a>
      </div>
    </form>
    <form class="form-signin custom-form" v-else>
      <div class="text-center w-100">
        <img class="mb-4" src="../assets/logo.png" alt="" height="100">
      </div>
      <h1 class="h3 mb-3 font-weight-normal text-center title">Fazer Cadastro</h1>
      <label for="inputName" class="sr-only">Nome</label>
      <input type="name" v-model="register.name" id="inputName" class="form-control mb-3" placeholder="Seu nome" required
        autofocus>
      <label for="inputEmail" class="sr-only">Endereço de email</label>
      <input type="email" v-model="register.email" id="inputEmail" class="form-control mb-3" placeholder="Seu email"
        required>
      <label for="inputPassword" class="sr-only">Senha</label>
      <input type="password" v-model="register.password" id="inputPassword" class="form-control" placeholder="Senha"
        required>
      <div class="text-center w-100 mt-3">
        <button class="btn btn-lg btn-primary btn-block w-100 mb-2 custom1" type="button"
          @click.prevent="SendRegister()">Cadastrar</button>
        <a href="" @click.prevent="is_register = false">Login</a>
      </div>
    </form>
  </div>
</template>
<script>
import api from "@/services/api";
import Swal from 'sweetalert2'
export default {
  data() {
    return {
      is_register: false,
      login: {
        email: null,
        password: null,
      },
      register: {
        email: null,
        password: null,
        name: null
      }

    }
  },
  methods: {
    async SendLogin() {
      await api.post("/login", this.login).then((response) => {
        if (response.data.user.token) {
          localStorage.setItem('token', response.data.user.token)
          this.$router.push({ name: 'home' })
        } else {
          Swal.fire({
            title: 'OPPS',
            text: 'Confira seu e-mail e senha',
            icon: 'error',

          });
        }
      }).catch(function (error) {
        Swal.fire({
          title: 'OPPS',
          text: error.response.data.msg ?? 'Confira seu e-mail e senha',
          icon: 'error',
        });
      });
    },

    async SendRegister() {
      await api.post("/register", this.register).then((response) => {
        if (response.data.msg) {
          Swal.fire({
            title: 'OPPS',
            text: response.data.msg,
            icon: 'error',

          });
        } else {
          Swal.fire({
            title: 'Sucesso',
            text: 'Cadastro realizado com sucesso',
            icon: 'success',

          });
          this.is_register = false
        }
      }).catch(function (error) {
        Swal.fire({
          title: 'OPPS',
          text: error.response.data.msg ?? 'Confira seus dados',
          icon: 'error',
        });
      });
    }
  },
}
</script>
<style>
.login {
  height: 100vh;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-align: center;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.title {
  color: rgb(9, 17, 83);
  background-color: rgba(85, 202, 238, 0.541);
  border-radius: 30px;

}

.form-signin {
  width: 100%;
  max-width: 430px;
  padding: 20px;
  margin: auto;
}

.form-signin .checkbox {
  font-weight: 200;
}

.custom1 {
  border-radius: 50px;
}

.form-signin .form-control {
  position: relative;
  box-sizing: border-box;
  height: auto;
  padding: 10px;
  font-size: 16px;
}

.form-signin .form-control:focus {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.custom-form {
  background-color: #91ee9e;
  padding: 20px;
  border-radius: 10px;
}

.custom-form label,
.custom-form input,
.custom-form button,
.custom-form a {
  color: rgb(0, 0, 0);
}


.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
