import { Component, OnInit, ElementRef } from '@angular/core';
import { PetType } from "../../models/pettype";
import { PetTypeService } from "../../servicios/pet-type.service";

@Component({
  selector: 'app-pettype-list',
  templateUrl: './pettype-list.component.html',
  styleUrls: ['./pettype-list.component.css']
})
export class PettypeListComponent implements OnInit {
  public petypes:PetType[];
  public is_insert: boolean = false;
  current_edit: {
    pettype: PettypeListComponent,
    input:any
  };
  selId=-1;
  constructor(private servicioPetType: PetTypeService, private elRef:ElementRef) {
    this.pettypes=<PetType[]>[],
    this.current_edit= {pettype: null, input:null};
   }

  ngOnInit(): void {
    this.servicioPetType.getPettypes().subscribe(
      tiposanimalicos => this.pettypes = tiposanimalicos,
      error => console.log(error)
    );
  }

  showAddPettypeComponent(){
    this.is_insert = !this.is_insert;
  }

  onNewPettype(new_pettype:PetType) {
    this.pettypes.push(new pettype);
    this.showAddPettypeComponent();
  }

}
