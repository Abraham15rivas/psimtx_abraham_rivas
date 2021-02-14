<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header vino">Dashboard</div>
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
                            <select v-model="self_interests" id="interest" multiple style="width:100%" :size="size_interest">
                                <option 
                                    v-for="(interest, index) of interests" 
                                    :key="index" 
                                    :value="{'id': interest.id, 'name': interest.name}"
                                    v-text="interest.name"
                                ></option>
                            </select>
                        </div>
                        <div class="body-footer text-right mt-2 ">
                            <button class="btn btn-success" :disabled="!self_interests.length > 0" @click="setInterest">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header vino">Usuarios con los mismos intereses</div>
                    <div class="card-body body-card">
                        <div v-if="related_users">
                            <h3>Intereses {{ count }} segundos...</h3>
                            <ul>
                                <ol v-for="(interest, index) of related_users" :key="index" v-text="interest"></ol>
                            </ul>
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
                count: 1,
                user: null,
                interests: [],
                size_interest: 0,
                self_interests: [],
                related_users: []
            }
        },
        methods: {
            interestSelected(id) {
                if(this.self_interests.length > 0) {
                    let filter = this.self_interests.filter((element) => element.id == id)
                    if(filter != "") {
                        return true
                    } else {
                        console.log('false')
                        return false
                    }
                }
            },
            async setInterest() {
                try {
                    const url = `/interest/store`
                    let params = {
                        interests: this.self_interests
                    }
                    let response = await axios.post(url, params)
                    if(response) {
                        console.log(response.data)
                        this.self_interests = response.data
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Guardado correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
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
            setInterval(() => {
                if(this.count < 30) {
                    this.count++  
                } else {
                    this.count = 1
                }
            }, 1000)
        }
    }
</script>
