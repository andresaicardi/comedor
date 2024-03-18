<template>
    <div>
        <el-row :gutter="24">
            <el-form ref="form" :model="form" :rules="rulesForm">
                <el-col :span="12">
                    <el-form-item label="Legajo">

                        <el-input v-model="form.legajo"></el-input>

                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item label="Semana">

                        <el-date-picker
                            v-model="form.semana"
                            type="week"
                            format="yyyy-WW"
                            value-format="yyyy-MM-dd"
                            placeholder="Selecciona una semana"
                            style="width: 100%;"
                        >
                        </el-date-picker>

                    </el-form-item>
                </el-col>
            </el-form>
        </el-row>

        <el-row :gutter="24">
            <el-col :span="6">
                <el-button type="success" @click="getPedidoInvitado()">Buscar</el-button>
            </el-col>
        </el-row>

        <div v-show="showTabla">
            <h2 style="text-align: center; color:red">{{this.nombre}}</h2>
            <el-table
                
                max-height="300"
                border
                stripe
                :data="allDatos"
                style="width: 100%; text-align: center;">
                <el-table-column
                    prop="dia_semana"
                    label="Dia"
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="hora"
                    label="Hora"
                    width="180">
                </el-table-column>
                <el-table-column
                        label="Pan"
                        width="180">
                        <template slot-scope="scope">
                            <span v-if="scope.row.pan===1">Si</span>
                            <span v-else>No</span>
                        </template>
                    </el-table-column>
                <el-table-column
                    prop="postre_description"
                    label="Postre"
                    width="180">
                </el-table-column>
                <el-table-column
                    prop="menu_description"
                    label="Menu">
                </el-table-column>
            </el-table>
        </div>

        <!-- <el-row v-for="(opcion, index) in allDatos" :key="index" :gutter="24">
            <h2 style="text-align: center" >{{ index }}</h2>
            <el-col v-for="menu in opcion.menu" :key="menu.id_menu" :offset="4" :span="8">
                <el-card class="box-card editMenuUsuario">
                    <div class="text item">
                        {{ menu.menu_description }}
                    </div>
                    <div class="text item">
                        Hora: {{ menu.hora }}
                    </div>
                </el-card>
            </el-col>
            <el-col v-for="postre in opcion.postre" :key="postre.id_postre" :span="8">
                <el-card class="box-card editMenuUsuario">
                    <div class="text item">
                        {{ postre.postre_description }}
                    </div>
                    <div class="text item">
                        Hora: {{ postre.hora }}
                    </div>
                </el-card>
            </el-col>
            <br>
        </el-row> -->
        
    </div>
</template>

<script>
import { Loading } from 'element-ui';
export default {
    data() {
        return {
            allDatos:[],
            showTabla:false,
            nombre:'',
            form:{
                semana:'',
                legajo:'',
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
        getPedidoInvitado(){
            let loadingInstance = Loading.service();
            let fecha=this.form.semana;
            let legajo=this.form.legajo;
            axios.get('/menu/getPedidoInvitado/'+legajo+'/'+fecha)
            .then(response =>{
                if(response.data.message=="No se encontro ningun dato expecifico de esta semana."){
                    this.$message({
                        showClose: true,
                        message: "No se encontro ningun dato expecifico de esta semana.",
                        type: 'error'
                    });
                    loadingInstance.close();
                }else if(response.data.message=="Exito!"){
                    this.allDatos=response.data.allDatos;
                    this.nombre=response.data.nombre;
                    this.showTabla=true;
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
    .editMenuUsuario .el-card__body{
        text-align: center;
    }
</style>