import { Component, OnInit, Input } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { PetsAndVisitService } from 'src/app/servicios/pets-and-visit.service';
import { Pet } from 'src/app/models/pet';


@Component({
  selector: 'app-pets-and-visit',
  templateUrl: './pets-and-visit.component.html',
  styleUrls: ['./pets-and-visit.component.css']
})
export class PetsAndVisitComponent implements OnInit {

  public pets:Array<Pet>;
  @Input()ide;

  constructor(private servicioPet:PetsAndVisitService,private ruta: Router, private route: ActivatedRoute) { }

  ngOnInit() {
    this.servicioPet.getPetsByOwner(this.ide).subscribe(datos=>{
      this.pets=datos;
      console.log(this.pets);
    });
  }

  pintaPetOwnerId(iden:any){

  }

}
