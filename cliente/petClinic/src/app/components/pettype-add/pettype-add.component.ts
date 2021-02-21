import { Pettype } from './../../models/pettype';
import { PetTypesService } from './../../servicios/pet-types.service';
import { Component, OnInit, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-pettype-add',
  templateUrl: './pettype-add.component.html',
  styleUrls: ['./pettype-add.component.css']
})
export class PettypeAddComponent implements OnInit {

  public pettype: Pettype;

  @Output() onNuevo = new EventEmitter<Pettype>();

  constructor(private servicioPetType: PetTypesService) {
    this.pettype = <Pettype>{};
  }

  ngOnInit(): void {

  }

  onSubmit(pettype: Pettype){
    this.pettype.id = null;

    this.servicioPetType.addPetType(pettype).subscribe(nuevoTipo => {
      console.log(nuevoTipo);

      this.pettype = nuevoTipo;
      this.onNuevo.emit(this.pettype);
    },
      error => console.log(error));
  }


}
