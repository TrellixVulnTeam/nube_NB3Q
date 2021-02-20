import { Component, OnInit } from '@angular/core';
import { PetTypeService } from 'src/app/servicios/pet-type.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Pettype } from 'src/app/models/pettype';

@Component({
  selector: 'app-pet-types',
  templateUrl: './pet-types.component.html',
  styleUrls: ['./pet-types.component.css']
})
export class PetTypesComponent implements OnInit {

  public tipos:Pettype[];

  constructor(private servicioPetType:PetTypeService,private ruta: Router, private route: ActivatedRoute) { 
    this.tipos=[];
  }

  ngOnInit() {
    this.servicioPetType.getPetTypes().subscribe(datos=>{
      this.tipos=datos;
    });
  }

  cambiaEdit(){
    
  }
}
