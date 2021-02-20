import { Component, OnInit, Input, Output, EventEmitter  } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { PetService } from 'src/app/servicios/pet.service';
import { Pet } from 'src/app/models/pet';



@Component({
  selector: 'app-pets',
  templateUrl: './pets.component.html',
  styleUrls: ['./pets.component.css']
})
export class PetsComponent implements OnInit {

 
  @Input()pet:Pet;

  @Output()eliminar = new EventEmitter();

  constructor(private servicioPet:PetService,private ruta: Router, private route: ActivatedRoute) { }

  ngOnInit() {

  }

  eliminarPet(iden:any){
    if(confirm("¿Desea eliminar al Pet?")){
      this.servicioPet.delPet(iden).subscribe(datos =>{
        console.log("emitimos: "+datos);
        this.eliminar.emit(datos);
      });
    }
  }

  actualizaVisitsEliminados(evento:any){
    if(evento.result=="OK"){
			this.eliminar.emit(evento);
	  }
  }

  irAmodPet(){
    this.ruta.navigate(['/form-pet-mod/'+this.pet.id]);
  }

  modificarPet(animal:Pet){
      this.servicioPet.modPet(animal).subscribe(datos =>{
        
        this.servicioPet.getPetsByOwner(this.pet.id).subscribe(datos=>{
          
        });

      });
  }


}
