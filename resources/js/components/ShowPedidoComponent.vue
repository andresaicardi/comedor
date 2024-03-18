<template>
    <div>
        <el-row :gutter="24">
            <el-form ref="form" :model="form" :rules="rulesForm">
                <el-col :span="12">
                    <el-form-item label="Semana">
                        <el-date-picker
                            v-model="form.semana"
                            type="week"
                            format="yyyy-WW"
                            value-format="yyyy-MM-dd"
                            placeholder="Selecciona una semana"
                            style="width: 100%;"
                        ></el-date-picker>
                    </el-form-item>
                </el-col>
            </el-form>
        </el-row>
        <el-row :gutter="24">
            <el-col :span="6">
                <el-button type="success" @click="getPedidos()">Buscar</el-button>
            </el-col>
        </el-row>
        <el-row v-for="(opcion, index) in allDatos" :key="index" :gutter="24">
            <h2 style="text-align: center" >{{ index }}</h2>
            
            <el-row>
                <el-col :span="18" :offset="3">
                    <span class="titulos-tablas">Menus</span>
                    <el-table
                        max-height="300"
                        border
                        stripe
                        :data="opcion.menu"
                        style="width: 100%; text-align: center;">
                        <el-table-column
                            prop="cantidad"
                            label="Cantidad"
                            width="180">
                        </el-table-column>
                        <el-table-column
                            prop="menu_description"
                            label="Menu">
                        </el-table-column>
                    </el-table>
                </el-col>
            </el-row>
            <br>
            <el-row>
                <el-col :span="18" :offset="3">
                    <span class="titulos-tablas">Postres</span>
                    <el-table
                        max-height="300"
                        border
                        stripe
                        :data="opcion.postre"
                        style="width: 100%; text-align: center;">
                        <el-table-column
                            prop="cantidad"
                            label="Cantidad"
                            width="180">
                        </el-table-column>
                        <el-table-column
                            prop="postre_description"
                            label="Postre">
                        </el-table-column>
                    </el-table>
                </el-col>
            </el-row>
            
            
            <br>
        </el-row>
        
    </div>
</template>

<script>
import { Loading } from 'element-ui';
export default {
    data() {
        return {
            allDatos:[],
            form:{
                semana:'',
            },
            rulesForm:{
                    
            },
        }
    },
    created(){
        
    },
    mounted(){
        
    },
    methods: {
        getPedidos(){
            let loadingInstance = Loading.service();
            let fecha=this.form.semana;
            axios.get('/menu/getPedidos/'+fecha)
            .then(response =>{
                if(response.data.message=="No se encontro ningun dato expecifico de esta semana"){
                    this.$message({
                        showClose: true,
                        message: "No se encontro ningun dato expecifico de esta semana",
                        type: 'error'
                    });
                    loadingInstance.close();
                }else if(response.data.message=="Exito!"){
                    this.allDatos=response.data.allDatos;
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
        
    },
};
</script>
<style>
    .editPedido .el-card__body{
        display: flex;
        justify-content: space-between;
    }

    .titulos-tablas{
        font-weight: 600;
    }
</style>