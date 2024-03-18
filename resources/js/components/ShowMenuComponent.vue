<template>
    <div>
        <el-form ref="form" :model="form" :rules="rulesForm">
            <el-row :gutter="24">
                <el-col :span="12">
                    <el-form-item prop="dia" label="Dia de la semana">
                        <el-date-picker
                            @change="getMenu(form.dia)" 
                            v-model="form.dia"
                            type="date"
                            value-format='yyyy-MM-dd'
                            placeholder="Elegir el dia"
                            :picker-options="pickerOptions"
                            style="width: 100%;"
                        >
                        </el-date-picker>
                    </el-form-item>
                </el-col>
                <el-col :span="6" >
                    <br>
                    <br>
                    <el-tag v-if="existeMenu" type="danger" effect="dark">Este dia no tiene menu asociado</el-tag>
                </el-col>
            </el-row >
            <el-row :gutter="24" v-if="mostrarMenu">
                <el-col :span="8">
                    <el-form-item prop="menu1" label="1er Menú">
                        <el-input
                            type="textarea"
                            :rows="2"
                            placeholder="Ingrese el primer menú"
                            v-model="form.menu1">
                        </el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item prop="menu2" label="2do Menú">
                        <el-input
                            type="textarea"
                            :rows="2"
                            placeholder="Ingrese el segundo menú"
                            v-model="form.menu2">
                        </el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item prop="menu3" label="3er Menú">
                        <el-input
                            type="textarea"
                            :rows="2"
                            placeholder="Ingrese el tercer menú"
                            v-model="form.menu3">
                        </el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="1er Postre" prop="postre1">
                        <el-select v-model="form.postre1" class="fullWidth" allow-create placeholder="Selecciona o agrega un nuevo elemento">
                            <el-option
                                v-for="item in postres"
                                :key="item.id_postre"
                                :label="item.postre_description"
                                :value="item.id_postre"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="2er Postre" prop="postre2">
                        <el-select v-model="form.postre2" class="fullWidth" allow-create placeholder="Selecciona o agrega un nuevo elemento">
                            <el-option
                                v-for="item in postres"
                                :key="item.id_postre"
                                :label="item.postre_description"
                                :value="item.id_postre"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="8">
                    <el-form-item label="3er Postre" prop="postre3">
                        <el-select v-model="form.postre3" class="fullWidth" allow-create placeholder="Selecciona o agrega un nuevo elemento">
                            <el-option
                                v-for="item in postres"
                                :key="item.id_postre"
                                :label="item.postre_description"
                                :value="item.id_postre"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                        <el-button v-if="createdMenu" @click="createMenu('form')" type="success">Crear Menú</el-button>
                        <el-button v-if="updatedMenu" @click="updateMenu('form')" type="success">Actualizar Menú</el-button>
                        <el-button @click="dialogVisible=true" type="primary">Agregar Postre</el-button>
                        <el-button @click="dialogEliminar=true" type="danger">Eliminar Postre</el-button>
                </el-col>
            </el-row>
        </el-form>

        <el-dialog title="Eliminar Postre" :visible.sync="dialogEliminar" @close="closePostreDesactivar" :close-on-click-modal="false" :close-on-press-escape="false" width="30%" >
            <el-row :gutter="24">
                <el-form ref="formEliminarPostre" :model="formEliminarPostre" :rules="rulesFormEliminarPostre">
                    <el-col :span="24">
                        <el-form-item label="Postre" prop="postre1">
                            <el-select v-model="formEliminarPostre.id" class="fullWidth" allow-create placeholder="Selecciona o agrega un nuevo elemento">
                                <el-option
                                    v-for="item in postres"
                                    :key="item.id_postre"
                                    :label="item.postre_description"
                                    :value="item.id_postre"
                                ></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12" :offset="12" class="alinearDerecha">
                        <el-button @click="desactivarPostre('formEliminarPostre')" type="danger">Eliminar</el-button>
                    </el-col>
                </el-form>
            </el-row>
        </el-dialog>

        <el-dialog title="Agregar Postre" :visible.sync="dialogVisible" @close="closePostre" :close-on-click-modal="false" :close-on-press-escape="false" width="30%" >
            <el-row :gutter="24">
                <el-form ref="formPostre" :model="formPostre" :rules="rulesFormPostre">
                    <el-col :span="24">
                        <el-form-item prop="nuevoPostre" label="Postre">
                            <el-input
                                placeholder="Ingrese el nuevo Postre"
                                v-model="formPostre.nuevoPostre">
                            </el-input>
                          </el-form-item>
                    </el-col>
                    <el-col :span="12" :offset="12" class="alinearDerecha">
                        <el-button @click="createPostre('formPostre')" type="success">Agregar</el-button>
                    </el-col>
                </el-form>
            </el-row>
        </el-dialog>
    </div>
</template>

<script>
    import { Loading } from 'element-ui';
    export default {
        data() {
            return {
                existeMenu:false,
                mostrarMenu:false,
                createdMenu:false,
                updatedMenu:false,
                dialogVisible:false,
                dialogEliminar:false,
                
                postres:[],

                form:{
                    id:'',
                    dia:'',
                    menu1:'',
                    id_menu1:'',
                    menu2:'',
                    id_menu2:'',
                    menu3:'',
                    id_menu3:'',
                    postre1:'',
                    postre2:'',
                    postre3:'',
                },

                rulesForm:{
                    dia:[{required: true, message: 'El dia es requerido', trigger: 'blur'}],
                    menu1:[{required: true, message: 'El Menú es requerido', trigger: 'blur'}],
                    menu2:[{required: true, message: 'El Menú es requerido', trigger: 'blur'}],
                    menu3:[{required: true, message: 'El Menú es requerido', trigger: 'blur'}],
                    postre1:[{required: true, message: 'El Postre es requerido', trigger: 'blur'}],
                    postre2:[{required: true, message: 'El Postre es requerido', trigger: 'blur'}],
                    postre3:[{required: true, message: 'El Postre es requerido', trigger: 'blur'}],
                },

                formEliminarPostre:{
                    id:''
                },

                rulesFormEliminarPostre:{
                    id:[{required: true, message: 'Elija un postre es requerido', trigger: 'blur'}],
                },
                
                formPostre:{
                    nuevoPostre:'',
                },

                rulesFormPostre:{
                    nuevoPostre:[{required: true, message: 'El nuevo postre es requerido', trigger: 'blur'}],
                },

                pickerOptions: {
                    disabledDate(time) {
                        // Deshabilitar fines de semana
                        const day = time.getDay();
                        if (day === 0 || day === 6) {
                            return true;
                        }

                        // Obtener la fecha del primer día de la semana actual (lunes)
                        const today = new Date();
                        const firstDayOfWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + (today.getDay() === 1 ? 0 : (today.getDay() === 0 ? -6 : 1)));

                        // Deshabilitar fechas anteriores al primer día de la semana actual
                        return time < firstDayOfWeek;
                    },
                },
            };
        },
        created(){
            
        },
        mounted(){
            this.getPostre();
        },
        methods: {

            getPostre(){
                let loadingInstance = Loading.service();
                axios.get('/menu/getPostre').then(response => {
                    if(response.data){
                        this.postres=response.data.postre;
                    }
                    loadingInstance.close();
                }).catch(error => {
                    console.log(error);
                    this.$message({
                        showClose: true,
                        message: error,
                        type: 'error'
                    });
                    loadingInstance.close();
                });
            },

            getMenu(dia){
                this.form.id='';
                this.form.menu1='';
                this.form.menu2='';
                this.form.menu3='';
                this.form.id_menu1='';
                this.form.id_menu2='';
                this.form.id_menu3='';
                this.form.postre1='';
                this.form.postre2='';
                this.form.postre3='';
    
                this.mostrarMenu=false;
                this.existeMenu=false;
                this.updatedMenu=false;
                this.createdMenu=false;
                if(dia!=null && dia!=undefined && dia!=''){
                    axios.get('/menu/getMenu/'+dia).then(response => {
                        if(Object.keys(response.data).length !== 0){
                            this.form.id=response.data.id_menu_dia;
                            this.form.menu1=response.data.menu_description1;
                            this.form.menu2=response.data.menu_description2;
                            this.form.menu3=response.data.menu_description3;
                            this.form.id_menu1=response.data.id_menu1;
                            this.form.id_menu2=response.data.id_menu2;
                            this.form.id_menu3=response.data.id_menu3;
                            this.form.postre1=response.data.id_postre1;
                            this.form.postre2=response.data.id_postre2;
                            this.form.postre3=response.data.id_postre3;
                            this.mostrarMenu=true;
                            this.updatedMenu=true;
                        }else{
                            this.mostrarMenu=true;
                            this.existeMenu=true;
                            this.createdMenu=true;
                        }
                        loadingInstance.close();
                    }).catch(error => {
                        this.$message({
                            showClose: true,
                            message: error,
                            type: 'error'
                        });
                        loadingInstance.close();
                    });
                }
                
            },

            createMenu(form){
                let loadingInstance = Loading.service();
                this.$refs[form].validate((valid) => {   
                    if(valid){
                        let form = this.form;
                        axios.post('/menu/createMenu', form)
                        .then(response => {
                            if(response.data.message=='Se creo el Menú con exito!'){
                                this.$message({
                                    showClose: true,
                                    message: 'Se creo el Menú con exito!',
                                    type: 'success'
                                });
                                this.$refs['form'].resetFields();
                                this.mostrarMenu=false;
                                this.existeMenu=false;
                                this.updatedMenu=false;
                                this.createdMenu=false;
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
                            console.log(error);
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

            updateMenu(form){
                let loadingInstance = Loading.service();
                this.$refs[form].validate((valid) => {   
                    if(valid){
                        let form = this.form;
                        axios.post('/menu/updateMenu', form)
                        .then(response => {
                            if(response.data.message=='Se actualizo el Menú con exito!'){
                                this.$message({
                                    showClose: true,
                                    message: 'Se actualizo el Menú con exito!',
                                    type: 'success'
                                });
                                this.$refs['form'].resetFields();
                                this.mostrarMenu=false;
                                this.existeMenu=false;
                                this.updatedMenu=false;
                                this.createdMenu=false;
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

            closePostre(){
                this.$refs['formPostre'].resetFields();
            },

            closePostreDesactivar(){
                this.formEliminarPostre.id='';
            },

            createPostre(form){
                let loadingInstance = Loading.service();
                this.$refs[form].validate((valid) => {   
                    if(valid){
                        let form = this.formPostre;
                        axios.post('/menu/createPostre', form)
                        .then(response => {
                            if(response.data.message=='Se creo el Postre con exito!'){
                                this.$message({
                                    showClose: true,
                                    message: 'Se creo el Postre con exito!',
                                    type: 'success'
                                });
                                this.$refs['formPostre'].resetFields();
                                this.getPostre()
                                this.dialogVisible=false;
                                loadingInstance.close();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: 'Ocurrio un inconveniente. Contactar con Sistemas',
                                    type: 'error'
                                });
                                this.getPostre();
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

            desactivarPostre(form){
                let loadingInstance = Loading.service();
                this.$refs[form].validate((valid) => {   
                    if(valid){
                        let form = this.formEliminarPostre;
                        axios.post('/menu/desactivarPostre', form)
                        .then(response => {
                            if(response.data.message=='Se elimino el postre!'){
                                this.$message({
                                    showClose: true,
                                    message: 'Se creo el Postre con exito!',
                                    type: 'success'
                                });
                                this.formEliminarPostre.id='';
                                this.getPostre()
                                this.dialogEliminar=false;
                                loadingInstance.close();
                            }else{
                                this.$message({
                                    showClose: true,
                                    message: 'Ocurrio un inconveniente. Contactar con Sistemas',
                                    type: 'error'
                                });
                                this.getPostre();
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
        },
    };
</script>

<style>

    

</style>