import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { AlumnoService } from 'src/app/servicios/alumno.service';
import { EstadocivilService } from 'src/app/servicios/estadocivil.service';
import { Alumno } from 'src/app/modelos/alumno';
import { Estadocivil } from 'src/app/modelos/estadocivil';

@Component({
  selector: 'app-alumno-form',
  templateUrl: './alumno-form.component.html',
  styleUrls: ['./alumno-form.component.css']
})
export class AlumnoFormComponent implements OnInit {

  public alumno:Alumno;
  public boton:string = "Añadir";
  public sexos:Estadocivil[];
  public estados:Estadocivil[];
  public sexi:any;
  public ec:any;
  public iden:number;

  constructor(private servicioEC:EstadocivilService,private servicioAlum:AlumnoService,private ruta: Router, private route: ActivatedRoute) { 
    this.alumno=<Alumno>{};
    this.sexos=[];
    this.estados=[];
  }

  ngOnInit() {
    this.iden = this.route.snapshot.params["id"];

    this.servicioEC.getSexos().subscribe(datos=>{
      console.log("array sexos: ",datos);
      this.sexos=datos;
    });
    this.servicioEC.getEstadosCiviles().subscribe(datos=>{
      console.log("array estados civiles: ",datos);
      this.estados=datos;
    });


  }

  anadeMod(){
    if(this.iden){
      this.boton="Modificar";
      this.servicioAlum.cargaAlumId(this.iden).subscribe(datos=>{
        this.alumno=<Alumno>{
          NOMBRE: datos.NOMBRE,
          APELLIDOS: datos.APELLIDOS,
          SEXO: this.sexi.CODIGO,
          FECHA_NACIMIENTO: datos.FECHA_NACIMIENTO,
          ESTADO_CIVIL: this.ec.ID,
          ID: this.iden
        };
      });
      this.modificaAlum();
    }
    else{
      this.boton="Añadir";
      this.anadeAlumno();
    }
  }

  anadeAlumno(){
    console.log("el sexo: ",this.sexi);
    console.log("el estado civil: ", this.ec);
    this.alumno=<Alumno>{
      NOMBRE: this.alumno.NOMBRE,
      APELLIDOS: this.alumno.APELLIDOS,
      SEXO: this.sexi.CODIGO,
      FECHA_NACIMIENTO: this.alumno.FECHA_NACIMIENTO,
      ESTADO_CIVIL: this.ec.ID
      
      
    };
    this.servicioAlum.anadeAlumno(this.alumno).subscribe(datos=>{
      this.ruta.navigate(['/alumnos-list']);
    });
  }

  modificaAlum(){
    this.servicioAlum.modificaAlumno(this.alumno).subscribe(datos=>{

    });
  }

}
