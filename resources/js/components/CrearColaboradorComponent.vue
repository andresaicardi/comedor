<template>
    <div>
        <el-row :gutter="24">
            <el-form ref="form" :model="form">
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
                <el-col :span="6">
                    <br>
                    <br>
                    <el-button type="success" @click="postCrearColaborador('form')">Crear</el-button>
                </el-col>
                
            </el-form>
        </el-row>
        
    </div>
</template>


<script>
import { Loading } from 'element-ui';
export default {
    
    data() {
        return {
            form:{
                legajo:'',
                cedula:'',
                nombre:'',
                apellido:'',
                hora:''
            },

            horarios: [             
                { value: '11:10', label: '11:10' },
                { value: '11:50', label: '11:30' },
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
        
        postCrearColaborador(form){
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

                    axios.post('/rrhh/postCrearColaborador', form)
                    .then(response => {
                        if(response.data.message=='Se creo el colaborador con exito!'){
                            this.$message({
                                showClose: true,
                                message: 'Se creo el colaborador con exito!',
                                type: 'success'
                            });
                            this.form.legajo='';
                            this.form.cedula='';
                            this.form.nombre='';
                            this.form.apellido='';
                            this.form.hora='';
                            const qr = response.data.qr;
                            const nombreCompleto = response.data.nombreCompleto;
                            window.open('/rrhh/generarQr/'+qr+'/'+nombreCompleto);
                            loadingInstance.close();

                        }else if(response.data.message=='Ya existe un colobarador con el mismo Legajo'){
                            this.$message({
                                showClose: true,
                                message: 'Ya existe un colobarador con el mismo Legajo',
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
        }
    },
};
</script>
<style>

</style>