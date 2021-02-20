import { Component, OnInit } from '@angular/core';
import { OwnerService } from "../../servicios/owner.service";
import { Owner } from "../../models/owner";
import { PetService } from "../../servicios/pet.service";
import { Pet } from "../../models/pet";
import { Pettype } from "../../models/pettype";
import { ActivatedRoute, Router } from '@angular/router';
import { PetTypeService } from 'src/app/servicios/pet-type.service';
import { environment } from 'src/environments/environment';
import { Visit } from 'src/app/models/visit';
import { VisitService } from "../../servicios/visit.service";

@Component({
  selector: 'app-form-visit',
  templateUrl: './form-visit.component.html',
  styleUrls: ['./form-visit.component.css']
})
export class FormVisitComponent implements OnInit {

  public boton: string = "Añadir";
  public visit: Visit;
  private idpet: any;
  private idvisit: any;
  public mascota: Pet;

  constructor(private servicioVisit: VisitService, private servicioPet: PetService, private ruta: Router, private route: ActivatedRoute) {

    this.visit = {
      id: -1,
      petId: -1,
      visitDate: new Date,
      description: ""
    }
    this.mascota = <Pet>{};

  }

  ngOnInit() {

    this.idpet = this.route.snapshot.params["idPet"];
    this.idvisit = this.route.snapshot.params["idVisit"];
    if (this.idvisit == null) {
      this.boton="Añadir";
      this.servicioPet.cargaPetId(this.idpet).subscribe(datos => {
        this.mascota = datos;
        this.visit = {
          id: this.mascota.owner.id,
          petId: this.idpet,
          visitDate: new Date,
          description: ""
        }
      });
    }

    if(this.idvisit!=null){
      this.boton="Modificar";
      this.servicioPet.cargaPetId(this.idpet).subscribe(datos => {
        this.mascota = datos;
        this.visit = {
          id: this.mascota.owner.id,
          petId: this.idpet,
          visitDate: new Date,
          description: ""
        }
      });
      this.servicioVisit.getVisitId(this.idvisit).subscribe(datos=>{
        console.log("la visita: ",datos);
        this.visit = {
          id: datos.id,
          petId: this.idpet,
          visitDate: datos.visitDate,
          description: datos.description
        }
      });
    }

  }

  anadirModificar(visit:any){
    if(this.idvisit!=null){
      this.modificarVisita();
    }
    else{
      this.anadirVisita(visit);
    }
  }

  anadirVisita(objt: any) {
    console.log(objt);

    this.servicioVisit.anadeVisit(objt).subscribe(datos => {
      console.log("datos", datos);
      console.log("mascota", this.mascota);
      this.ruta.navigate(['/owners/' + this.mascota.owner.id]);
    });

  }

  modificarVisita(){
    this.servicioVisit.modVisit(this.visit).subscribe(datos=>{
      this.ruta.navigate(['/owners/' + this.mascota.owner.id]);
    });
  }

}
