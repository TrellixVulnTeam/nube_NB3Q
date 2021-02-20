import { Component, OnInit, Output, EventEmitter } from '@angular/core';

import { ActivatedRoute, Router } from "@angular/router";

import { PetType } from "../../models/pettype";
import { PetTypeService } from "../../servicios/pet-type.service";

@Component({
  selector: 'app-pettype-add',
  templateUrl: './pettype-add.component.html',
  styleUrls: ['./pettype-add.component.css']
})
export class PettypeAddComponent implements OnInit {
	pettype: PetType;
  @Output() onNew = new EventEmitter<PetType>();
  constructor(private servicioPetType: PetTypeService, private route: ActivatedRoute, private ruta: Router) {
		this.pettype = <PetType>{};
	 }

  ngOnInit() {
	}

	onSubmit(pettype: PetType){
	//	console.log(pettype);
		pettype.id = null;
    this.servicioPetType.addPetType(pettype).subscribe(
      new_pettype => {
				console.log(new_pettype);
				
        this.pettype = new_pettype;
        this.onNew.emit(this.pettype);
      },
      error => console.log(error)
		);
  }

}
