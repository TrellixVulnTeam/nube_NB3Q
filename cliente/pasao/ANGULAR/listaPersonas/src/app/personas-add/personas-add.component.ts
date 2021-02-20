import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";
import { PAjaxService } from '../p-ajax.service';
import { Persona } from '../persona';

@Component({
  selector: 'app-personas-add',
  templateUrl: './personas-add.component.html',
  styleUrls: ['./personas-add.component.css']
})
export class PersonasAddComponent implements OnInit {

  //private objetoPer:{dni: string, nombre: string, apellidos:string};
  
  private boton:string;
  private personaId;
  private persona:Persona;



  constructor(private peticion: PAjaxService, private ruta: Router, private route: ActivatedRoute) { 
    this.persona={
      id: -1,
      dni:"",
      nombre:"",
      apellidos:""
    };
    this.personaId = this.route.snapshot.params["id"];
  }

  ngOnInit() {
    console.log("id = " + this.personaId);

    if(this.personaId==-1){
      this.boton='Añadir';
    }
    else{
      this.cargaPersonas(this.personaId);
      this.boton='Modificar';
    }

  }

  cargaPersonas(iden: number) {
    console.log(iden);
    this.peticion.petiCargaPer(iden).subscribe(res => {
    console.log(res);
      this.persona=res;
    });
  }

  anadePersona(formu:any) {
    console.log("valor formu:"+formu.dni);
    if (this.personaId == -1) {

      //let objetoPER = { dni: this.persona.dni, nombre: this.persona.nombre, apellidos: this.persona.apellidos };
      console.log(this.persona);
      this.peticion.petiInsertar(this.persona).subscribe(res => {
      console.log(res);
      });

    }
    else{

      //let objetoPER = { dni: this.dni, nombre: this.nombre, apellidos: this.apellidos, id:this.personaId };
      console.log(this.persona);
      this.peticion.petiMod(this.persona).subscribe(res => {
      console.log(res);
      });

    }


  }

}
