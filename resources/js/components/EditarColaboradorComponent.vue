<template>
    <div>
        <el-row :gutter="24">
            <el-form ref="form" :model="form" :rules="rulesForm">
                <el-row v-show="showBusqueda" :gutter="12">
                    <el-col :span="8">
                        <el-form-item label="Legajo">
                            <el-input  v-model="form.legajoBusqueda"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row v-show="showBusqueda" :gutter="12">
                    <el-col :span="8">
                        <el-button type="success" @click="getColaborador(form.legajoBusqueda)">Buscar</el-button>
                    </el-col>
                </el-row>
                <br>
                <el-row v-show="showForm" :gutter="24">
                    <el-col :span="8">
                        <el-form-item label="Legajo">
                            <el-input  v-model="form.legajo"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Nombre">
                            <el-input v-model="form.nombre"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Apellido">
                            <el-input v-model="form.apellido"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Cedula">
                            <el-input v-model="form.cedula"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="legajo">
                            <el-input v-model="form.legajo"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Hora">
                            <el-select style="width: 100%;" v-model="form.hora" placeholder="Selecciona un horario">
                                    <el-option
                                        v-for="hora in horarios"
                                        :key="hora.value"
                                        :label="hora.label"
                                        :value="hora.value"
                                    ></el-option>
                                </el-select>
                        </el-form-item>
                    </el-col>
                    
                </el-row>
                <el-row v-show="showForm" :gutter="24">
                    <el-col :span="8">
                        <el-button type="success" @click="updatedColaborador('form')">Editar</el-button>
                        <el-button type="primary" @click="generarQr()">Ver QR</el-button>
                    </el-col>
                </el-row>
            
            </el-form>
        </el-row>
    </div>
</template>

<script>
    import { Loading } from 'element-ui';
    export default {
        data() {
            return {
                showForm: false,
                showBusqueda:true,
                form:{
                    legajoBusqueda:'',
                    id_colaborador:'',
                    legajo:'',
                    cedula:'',
                    nombre:'',
                    apellido:'',
                    hora:''
                },

                horarios: [             
                    { value: '11:10', label: '11:10' },
                    { value: '11:50', label: '11:50' },
                    { value: '12:10', label: '12:10' },
                    { value: '12:30', label: '12:30' },
                    { value: '13:00', label: '13:00' },
                ],

                rulesForm:{
                    legajo:[{required: true, message: 'El legajo es requerido', trigger: 'blur'}],
                    cedula:[{required: true, message: 'La cedula es requerida', trigger: 'blur'}],
                    nombre:[{required: true, message: 'El nombre es requerido', trigger: 'blur'}],
                    apellido:[{required: true, message: 'El apellido es requerido', trigger: 'blur'}],
                    apellido:[{required: true, message: 'La hora es requerido', trigger: 'blur'}],
                }
            };
        },
        created(){
            
        },
        mounted(){
            
        },
        methods: {
            getColaborador(legajo){
                let loadingInstance = Loading.service();
                axios.get('/rrhh/getColaborador/'+legajo)
                .then(response =>{
                    if(response.data.message=="No se encontro ningun dato expecifico del colaborador"){
                        this.$message({
                            showClose: true,
                            message: "No se encontro ningun dato expecifico del colaborador",
                            type: 'error'
                        });
                        loadingInstance.close();
                    }else if(response.data.message=="Exito!"){
                        const colaborador =response.data.colaborador;
                        this.form.id_colaborador=colaborador.id_colaborador;
                        this.form.legajo=colaborador.legajo;
                        this.form.cedula=colaborador.cedula;
                        this.form.nombre=colaborador.nombre;
                        this.form.apellido=colaborador.apellido;
                        this.form.hora=colaborador.hora;
                        this.showForm=true;
                        this.showBusqueda=false;
                        this.qr=this.form.legajo+'-'+this.form.cedula;
                        this.nombreCompleto=this.form.nombre+' '+this.form.apellido;
                        loadingInstance.close();
                    }else{
                        this.$message({
                            showClose: true,
                            message: 'Ocurrio un error, contactarse con el area de sistemas',
                            type: 'error'
                        });
                        loadingInstance.close();
                    }
                });
            },

            updatedColaborador(form){
                let loadingInstance = Loading.service();
                this.$refs[form].validate((valid) => {   
                    if(valid){
                        let form = this.form;
                        if(isNaN(this.form.legajo)){
                            this.$message({
                                showClose: true,
                                message: 'El legajo tiene que ser de tipo numerico',
                                type: 'error'
                            });
                            return false;
                        }

                        if(isNaN(this.form.cedula)){
                            this.$message({
                                showClose: true,
                                message: 'La cedula tiene que ser de tipo numerico',
                                type: 'error'
                            });
                            return false;
                        }

                        axios.post('/rrhh/postUpdatedColaborador', form)
                        .then(response => {
                            if(response.data.message=='Se modifico el colaborador con exito!'){
                                this.$message({
                                    showClose: true,
                                    message: 'Se modifico el colaborador con exito!',
                                    type: 'success'
                                });
                                this.form.legajoBusqueda='';
                                this.form.id_colaborador='';
                                this.form.legajo='';
                                this.form.cedula='';
                                this.form.nombre='';
                                this.form.apellido='';
                                this.form.hora='';
                                this.showForm=false;
                                this.showBusqueda=true;
                                loadingInstance.close();
                            }else if(response.data.message=='No se encontro ningun dato expecifico del colaborador'){
                                this.$message({
                                    showClose: true,
                                    message: "No se encontro ningun dato expecifico del colaborador",
                                    type: 'error'
                                });
                                loadingInstance.close();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: 'Ocurrio un inconveniente. Contactar con Sistemas',
                                    type: 'error'
                                });
                                loadingInstance.close();
                            }
                        }).catch(error => {
                            this.$message({
                                showClose: true,
                                message: error,
                                type: 'error'
                            });
                            loadingInstance.close();
                        });
                    }
                });
            },

            generarQr(){
                window.open('/rrhh/generarQr/'+this.qr+'/'+this.nombreCompleto);
            }
            
        },
    };
</script>
<style>

</style>