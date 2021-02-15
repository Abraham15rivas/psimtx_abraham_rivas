<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="mb-3 col-md-12 text-center">
                <h3>Panel Usuario</h3>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header vino">Datos e Intereses</div>
                    <div class="card-body body-card">
                        <div class="alert alert-success" role="alert" v-if="status">
                            {{ session('status') }}
                        </div>

                        <span v-if="user">
                            <h4>¡Has iniciado sesión! {{ user.name }}</h4>
                        </span>

                        <span v-if="self_interests != ''">
                            <h4>Mis intereses</h4>
                            {{ self_interests.map((ele) => ele.name) }}
                        </span>

                        <div class="mt-2" v-if="interests">
                            <h4>Elige tus intereses</h4>
                            <select v-model="self_interests" id="interest" name="self_interests[]" multiple="multiple" style="width:100%" :size="size_interest">
                                <option 
                                    v-for="(interest, index) of interests" 
                                    :key="index" 
                                    :value="{'id': interest.id, 'name': interest.name}"
                                    v-text="interest.name"
                                ></option>
                            </select>
                        </div>
                        <div class="body-footer text-right mt-2 ">
                            <button class="btn btn-primary" :disabled="!self_interests.length > 0" @click="cancel">cancelar</button>
                            <button class="btn btn-success" :disabled="!self_interests.length > 0" @click="setInterest">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header vino">
                        <div class="inline-block">Usuario con los mismos intereses (Mínimo 2 en común)</div>
                        <div class="preloader inline-block"></div>
                    </div>
                    <div class="card-body body-card">
                        <div v-if="related_users">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Intereses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(users, index) of related_users" :key="index">
                                        <td v-text="users.name"></td>
                                        <td v-text="users.email"></td>
                                        <td>
                                            <p>Intereses:</p>
                                            <ul v-for="(interest, indx) of users.interests" :key="indx">
                                                <li v-text="interest.name"></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Swal from 'sweetalert2'
    export default {
        props: {
            status: String,
        },
        data() {
            return {
                user: null,
                interests: [],
                size_interest: 0,
                self_interests: [],
                related_users: []
            }
        },
        methods: {
            cancel() {
                this.getInterests
                this.self_interests = this.user.interests
            },
            async setInterest() {
                try {
                    const url = `/interest/store`
                    let params = {
                        interests: this.self_interests
                    }
                    let response = await axios.post(url, params)
                    if(response) {
                        this.self_interests = response.data
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getInterests()
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async getUSer() {
                try {
                    const url = `/user`
                    let response = await axios.get(url)
                    if(response) {
                        this.user = response.data
                        if(this.user.status == false) {
                            Swal.fire({
                                title: 'Usuario bloqueado su sessión sera cerrada',
                                icon: 'warning',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'ok'
                            }).then((result) => {
                                axios.post('/logout').then((res) => {
                                    if (result.isConfirmed) {
                                        Swal.fire(
                                        'Hasta luego',
                                        'Bye...',
                                        'success'
                                        )
                                    }
                                    if(res) {
                                        window.location.reload()
                                    }
                                })
                            })
                        }
                        if(this.user.interests) {
                            this.self_interests = this.user.interests
                        }
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async getInterests() {
                try {
                    const url = `/interests`
                    let response = await axios.get(url)
                    if(response) {
                        this.interests = response.data
                        this.getUSer()
                        if(this.interests.length < 25) {
                            this.size_interest = this.interests.length
                        } else {
                            this.size_interest = 25
                        }
                    }
                } catch (error) {
                    console.log(error)
                }
            },
            async getRelatedUsers() {
                try {
                    const url = `/related/users`
                    let response = await axios.get(url)
                    if(response) {
                        this.related_users = response.data
                    }
                } catch (error) {
                    console.log(error)
                }
            }
        },
        watch: {
            self_interests() {
                if(this.self_interests.length > 5) {
                    this.self_interests.shift()
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Solo 5 intereses como máximo',
                    })
                }
            }

        },
        mounted() {
            this.getInterests()
            this.getRelatedUsers()
            setInterval(() => {
              this.getRelatedUsers()  
            }, 30000)
        }
    }
</script>

<style scoped>
.inline-block {
    display: inline-block;
}
.preloader {
    float: right;
    width: 10px;
    height: 10px;
    border: 10px solid #eee;
    border-top: 10px solid #666;
    border-radius: 50%;
    animation-name: girar;
    animation-duration: 2s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}
@keyframes girar {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>