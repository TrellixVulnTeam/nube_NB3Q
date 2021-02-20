import { Component, OnInit } from '@angular/core';
import { Vet } from "../../models/vet";
import { SpecialtyService } from 'src/app/servicios/specialty.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Specialty } from 'src/app/models/specialty';
import { VetService } from 'src/app/servicios/vet.service';


@Component({
  selector: 'app-form-vet',
  templateUrl: './form-vet.component.html',
  styleUrls: ['./form-vet.component.css']
})
export class FormVetComponent implements OnInit {

  public vete:Vet;
  public boton:string="Añadir";
  public especialidades:Specialty[];
  public idVet:any;

  constructor(private servicioVet:VetService,private servicioSpecial:SpecialtyService,private ruta: Router, private route: ActivatedRoute) { 
    this.vete=<Vet>{};
    this.especialidades=[];
  }

  ngOnInit() {
    this.idVet = this.route.snapshot.params["idVet"];
    this.servicioSpecial.getSpecialties().subscribe(datos=>{
      this.especialidades=datos;
    });

    if(this.idVet){
      this.boton="Modificar";
      this.servicioVet.getVetId(this.idVet).subscribe(datos=>{
        this.vete=datos;
      });
    }
    else{
      this.boton="Añadir";
    }
  }

  anadeMod(){
    if(this.idVet){
      this.modVet();
    }
    else{
      this.anadirVet();
    }
  }

  anadirVet(){
    console.log(this.vete);
   
    this.servicioVet.anadeVet(this.vete).subscribe(datos=>{
      this.ruta.navigate(['/vets']);
    });
  }

  modVet(){
    this.servicioVet.editVet(this.vete).subscribe(datos=>{
      this.ruta.navigate(['/vets']);
    });
  }

}
