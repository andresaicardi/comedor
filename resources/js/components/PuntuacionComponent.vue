<template>
    <div>
        <div v-if="scannerActive">
            <h1 class="qr-class">Escanea el QR para puntuar</h1><br>
            <div class="centered-container">
                <div class="contenedor-camara">
                <StreamBarcodeReader
                    @decode="login"
                    @loaded="onLoaded"
                ></StreamBarcodeReader>
                </div>
            </div>
        </div>
        <el-form v-if="mostrarForm" ref="formPuntuacion" :model="formPuntuacion" class="card-personalizada">
            <div class="block card-puntuacion">
                <span class="demonstration">Puntuacion del dia de hoy</span>
                <el-rate v-model="formPuntuacion.puntuacion"></el-rate>
            </div>
            <br>
            <el-row :gutter="24">
                <el-row :gutter="24">
                    <el-col :span="12" :offset="6">
                        <el-form-item label="Comentario">
                            <el-input
                                type="textarea"
                                v-model="formPuntuacion.comentario"
                                maxlength="255"
                                show-word-limit
                            ></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="24">
                    <el-col :span="12" :offset="6" class="alinearDerecha">
                        <el-button @click="enviarPuntacion('formPuntuacion')" type="success">Enviar</el-button>
                    </el-col>
                </el-row>
            </el-row>
        </el-form>

        <el-form v-if="mostrarFormMensual" ref="formMensual" :model="formMensual" class="card-personalizada">
            <div class="block card-puntuacion">
                <span class="demonstration">¿Cómo calificarías la calidad de la comida durante este mes?</span>
                <el-rate v-model="formMensual.pregunta1"></el-rate>
            </div>
            <br>
            <div class="block card-puntuacion">
                <span class="demonstration">¿Estás satisfecho con el tamaño de las porciones?</span>
                <el-rate v-model="formMensual.pregunta2"></el-rate>
            </div>
            <br>
            <div class="block card-puntuacion">
                <span class="demonstration">¿Como calificarías la variedad del menú ofrecido?</span>
                <el-rate v-model="formMensual.pregunta3"></el-rate>
            </div>
            <br>
            <div class="block card-puntuacion">
                <span class="demonstration">¿Cómo calificarías la higiene y el mantenimiento del área del comedor?</span>
                <el-rate v-model="formMensual.pregunta4"></el-rate>
            </div>
            <br>
            <el-row :gutter="24">
                <el-row :gutter="24">
                    <el-col :span="12" :offset="6" class="alinearDerecha">
                        <el-button @click="enviarMensual('formMensual')" type="success">Enviar</el-button>
                    </el-col>
                </el-row>
            </el-row>
        </el-form>

    </div>
</template>

<script>
import { StreamBarcodeReader } from "vue-barcode-reader";
export default {
    components: {
        StreamBarcodeReader,
    },
    data() {
        return {
            scannerActive:true,
            mostrarForm:false,
            mostrarFormMensual:false,

            formLogin:{
                qr:'',
            },

            formPuntuacion:{
                legajo:'',
                puntuacion:0,
                comentario:'',
            },

            formMensual:{
                legajo:'',
                pregunta1:0,
                pregunta2:0,
                pregunta3:0,
                pregunta4:0,
            },

        };
    },
    created(){
        
    },
    mounted(){
        
    },
    methods: {
        onLoaded() {
            console.log(`Ready to start scanning barcodes`)
        },

        getValorarMes(id_colaborador){
            axios.get('/colaborador/getValorarMes/'+id_colaborador)
            .then(response =>{
                if(response.data.message=='Realizar puntuacion mensual!'){
                    this.$message({
                        showClose: true,
                        message: 'Realizar puntuacion mensual!',
                        type: 'success'
                    });
                    this.mostrarFormMensual=true;
                    
                }else if(response.data.message=='Realizar puntuacion diaria!'){
                    this.$message({
                        showClose: true,
                        message: 'Realizar puntuacion diaria!',
                        type: 'success'
                    });
                    this.mostrarForm=true;
                }else{
                    this.$message({
                        showClose: true,
                        message: 'Ya se realizo la puntuacion diaria!',
                        type: 'error'
                    });
                    window.location.reload();
                }
            }).catch(error => {
                this.$message({
                    showClose: true,
                    message: error,
                    type: 'error'
                });
            });
        },

        async login(value){
            this.formLogin.qr=value;
            this.scannerActive=false;

            if(this.formLogin.qr=='SOMIL'){
                const csrf= document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                let form= {
                    csrf:csrf
                }
                const response = await axios.post('/login/logout', form);
                if (response.status === 200) {
                    window.location.href = '/';
                } else {
                    this.$message({
                        showClose: true,
                        message: 'Hubo un error a la hora de cerrar session',
                        type: 'error'
                    });
                }
            }
            
            let form = this.formLogin;
            axios.post('/colaborador/loginInvitado', form)
            .then(response => {
                if(response.data.message=='Bienvenido al Menú semana!'){
                    this.$message({
                        showClose: true,
                        message: 'Bienvenido puntua el menu de hoy!',
                        type: 'success'
                    });
                    const login =response.data.login;
                    this.formPuntuacion.legajo=login.legajo;
                    this.formMensual.legajo=login.legajo;
                    this.getValorarMes(login.id_colaborador);

                }else if(response.data.message=='No se encontro el usuario, el legajo no existe o no se encuentra'){
                    this.$message({
                        showClose: true,
                        message: 'No se encontro el usuario, el legajo no existe o no se encuentra',
                        type: 'error'
                    });
                    window.location.reload();
                }else if(response.data.message=='La contraseña no es correcta'){
                    this.$message({
                        showClose: true,
                        message: 'La contraseña no es correcta',
                        type: 'error'
                    });
                    window.location.reload();
                }else{
                    this.$message({
                        showClose: true,
                        message: 'Ocurrio un inconveniente. Contactar con Sistemas',
                        type: 'error'
                    });
                    window.location.reload();
                }
            }).catch(error => {
                this.$message({
                    showClose: true,
                    message: error,
                    type: 'error'
                });
                window.location.reload();
            });
        },

        enviarPuntacion(form){

            if(this.formPuntuacion.puntuacion===0){
                this.$message({
                    showClose: true,
                    message: 'La puntuacion no puede ser cero!',
                    type: 'error'
                });
            }

            this.$refs[form].validate((valid) => {   
                if(valid){
                    let form = this.formPuntuacion;
                    axios.post('/colaborador/postPuntuacion', form)
                    .then(response => {
                        if(response.data.message=='Se realizo la valoracion con exito!'){
                            this.$message({
                                showClose: true,
                                message: 'Se realizo la valoracion con exito!',
                                type: 'success'
                            });
                            window.location.reload();
                        }else{
                            this.$message({
                                showClose: true,
                                message: 'Ocurrio un inconveniente. Contactar con Sistemas',
                                type: 'error'
                            });
                        }
                    }).catch(error => {
                        this.$message({
                            showClose: true,
                            message: error,
                            type: 'error'
                        });
                    });
                }
            });
        },
        
        enviarMensual(form){
            if(this.formMensual.pregunta1===0 || this.formMensual.pregunta2===0 || this.formMensual.pregunta3===0 || this.formMensual.pregunta4===0){
                this.$message({
                    showClose: true,
                    message: 'La puntuacion no puede ser cero en ninguno de los casos!',
                    type: 'error'
                });
            }

            this.$refs[form].validate((valid) => {   
                if(valid){
                    let form = this.formMensual;
                    axios.post('/colaborador/postMensual', form)
                    .then(response => {
                        if(response.data.message=='Se realizo la valoracion con exito!'){
                            this.$message({
                                showClose: true,
                                message: 'Se realizo la valoracion con exito!',
                                type: 'success'
                            });
                            window.location.reload();
                        }else if(response.data.message=='Realizar puntuacion diaria!'){
                            this.$message({
                                showClose: true,
                                message: 'Realizar puntuacion diaria!',
                                type: 'success'
                            });
                            this.mostrarFormMensual=false;
                            this.mostrarForm=true;
                        }else{
                            this.$message({
                                showClose: true,
                                message: 'Ocurrio un inconveniente. Contactar con Sistemas',
                                type: 'error'
                            });
                        }
                    }).catch(error => {
                        this.$message({
                            showClose: true,
                            message: error,
                            type: 'error'
                        });
                    });
                }
            });
        }   

    },
};
</script>
<style>
     
</style>