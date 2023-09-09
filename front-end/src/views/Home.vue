<template>
  <div>
    <header>
      <div class="navbar navbar-info bg-info shadow-sm">
        <div class="container d-flex justify-content-center text-white">
          <strong>Galeria de Imagens</strong>
        </div>
      </div>
    </header>

    <main role="main">
      <section class="jumbotron text-center mt-4 bg-light py-5">
        <div class="container">
          <h3>Upload de imagens</h3>
          <form ref="formCadastro">
            <div class="row">
              <div class="col-12 col-md-4 text-start mb-2">
                <label class="sr-only">Título</label>
                <input type="text" v-model="register.title" class="form-control" placeholder="Título" required />
              </div>
              <div class="col-12 col-md-4 text-start mb-2">
                <label class="sr-only">Descrição</label>
                <input type="text" v-model="register.description" class="form-control" placeholder="Descrição" required />
              </div>
              <div class="col-12 col-md-4 text-start mb-2">
                <label class="sr-only">Categoria</label>
                <input type="text" v-model="register.category" class="form-control" placeholder="Categoria" required />
              </div>
              <div class="col-12 mt-3">
                <label class="filecapa w-100 cursor-pointer d-flex align-items-center justify-content-center">
                  <div class="uploadimg w-100 border p-1 text-center">
                    <span class="p-4" v-if="image == null">
                      <p class="m-0 p-0">Click para fazer o upload da imagem</p>
                    </span>
                    <span class="p-2" v-else>
                      <img :src="image.url" width="200" />
                    </span>
                  </div>
                  <input type="file" @change="onFileChange($event)" name="product_image" class="d-none" ref="fileImage" />
                </label>
              </div>
              <div class="col-12 mt-3">
                <button class="btn btn-md btn-primary btn-block w-100 mb-2" type="button"
                  @click.prevent="SendRegister()">Cadastrar</button>
              </div>
            </div>
          </form>
        </div>
      </section>

      <div class="album py-5 pt-1">
        <div class="container">
          <div class="row">

            <div class="col-12 mb-5">
              <div class="card">
                <div class="card-body">
                  <div class="input-group">
                    <input type="search" v-model="search" class="form-control" placeholder="Pesquisar"
                      aria-label="Pesquisar">
                    <button type="button" class="btn btn-sm btn-primary input-group-text"
                      @click.prevent="searchGalery()">Pesquisar</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-4"
              v-for="(galeria, index) in galerias.filter((galeria_filter) => galeria_filter.title.includes(search) || search == null)"
              :key="index">
              <div class="card mb-4 shadow-sm bg-light">
                <img class="card-img-top img-fluid" :src="`http://localhost:8000/` + galeria.image" :alt="galeria.title">
                <div class="card-body">
                  <p class="card-text">{{ galeria.title }}<br />{{ galeria.description }}</p>
                  <div class="w-100">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100"
                      @click.prevent="deleteImage(galeria.id)">Remover</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import api from "@/services/api";
import Swal from 'sweetalert2'
export default {
  data() {
    return {
      register: {
        title: null,
        description: null,
        category: null
      },
      image: null,
      galerias: [],
      search: null
    }
  },
  mounted() {
    this.getGategorias()
  },
  methods: {
    async onFileChange(e) {
      const file = e.target.files[0]
      this.image = {
        file: file,
        url: URL.createObjectURL(file),
        id: null,
        base: await this.getBase64(file)
      }
    },

    async deleteImage(id) {
      await api.delete("/galleries/" + id).then((response) => {
        Swal.fire({
          title: 'Sucesso',
          text: 'Imagem deletada com sucesso',
          icon: 'success',
        });
        this.getGategorias()
      }).catch(function (error) {
        Swal.fire({
          title: 'OPPS',
          text: error.response.data.msg ?? 'Algo deu errado',
          icon: 'error',
        });
      });
    },
    async SendRegister() {

      if (this.image != null) {
        this.register.image = this.image.base;
      }

      await api.post("/galleries", this.register).then((response) => {
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
          this.getGategorias()
          this.$refs.formCadastro.reset();
        }
      }).catch(function (error) {
        Swal.fire({
          title: 'OPPS',
          text: error.response.data.msg ?? 'Confira os dados',
          icon: 'error',
        });
      });
    },

    async getGategorias() {
      await api.get("/galleries").then((response) => {
        console.log("data", response.data)
        this.galerias = response.data
      }).catch(function (error) {
        this.galerias = []
      });
    },

    getBase64(file) {
      return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
      });
    },
  }

}

</script>
<style>
.filecapa {
  border: 2px dashed #ccc;
  width: 100%;
  background: #fff;
}

.is-invalid .multiselect__tags {
  border-color: #f46a6a !important;
  border: 1px solid #f46a6a !important;
}
</style>
