import { Component, OnInit } from '@angular/core';
import { OwnerService } from "../../servicios/owner.service";
import { Owner } from "../../models/owner";
import { PetService } from "../../servicios/pet.service";
import { Pet } from "../../models/pet";
import { Pettype } from "../../models/pettype";
import { ActivatedRoute, Router } from '@angular/router';
import { PetTypeService } from 'src/app/servicios/pet-type.service';
import { environment } from 'src/environments/environment';

@Component({
  selector: 'app-form-pet',
  templateUrl: './form-pet.component.html',
  styleUrls: ['./form-pet.component.css']
})
export class FormPetComponent implements OnInit {

  public pet:Pet;
  public boton:string;
  public iden:any;
  public idePet:any;
  public pet_types:Pettype[];
  public nombreProp:string;

  constructor(private servicioPetType:PetTypeService,private servicioOwner:OwnerService,private servicioPet:PetService,private ruta: Router, private route: ActivatedRoute) {
    this.pet= <Pet>{};
    this.pet_types = [];
    
  }

  ngOnInit() {

    this.boton="Añadir";
    this.iden = this.route.snapshot.params["id"];
    this.idePet = this.route.snapshot.params["idPet"];

    this.servicioPetType.getPetTypes().subscribe(
      datos=> {
        console.log("toy en oninit ese", datos);
        this.pet_types=datos;
      }
    );

    if(this.iden!=null){
      this.servicioOwner.getOwnerId(this.iden).subscribe(
        datos => {
          console.log(datos);
          this.pet.owner = datos;
          this.nombreProp = datos.firstName+" "+datos.lastName;
        }
      );
    }

    if(this.idePet!=null){
      this.boton="Modificar";
      this.servicioPet.cargaPetId(this.idePet).subscribe(
        datos=> {
          this.pet=datos;
          this.nombreProp = datos.owner.firstName+" "+datos.owner.lastName;
          this.pet.type = environment.seleccionarObj(this.pet_types, this.pet.type);
          console.log("en otro sitio", this.pet_types);
        }
      );
    }
    
    
  }

  anadeMod(){
    if(this.iden!=null){
      this.anadirPet();
    }
    if(this.idePet!=null){
      this.modificarPet();
    }
  }

  anadirPet(){
    //this.boton="Añadir";
    console.log(this.pet);
    this.servicioPet.anadePet(this.pet).subscribe(datos =>{
      console.log(datos);
      this.ruta.navigate(['/owners/'+this.iden]);
    });
  }

  modificarPet(){
    this.servicioPet.modPet(this.pet).subscribe(datos =>{
      
      this.servicioPet.getPetsByOwner(this.pet.id).subscribe(datos=>{
        this.ruta.navigate(['/owners/'+this.pet.owner.id]);
      });

    });
}

}
