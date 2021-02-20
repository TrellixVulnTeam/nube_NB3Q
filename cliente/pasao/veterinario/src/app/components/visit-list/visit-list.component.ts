import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { Visit } from 'src/app/models/visit';
import { VisitService } from 'src/app/servicios/visit.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Owner } from 'src/app/models/owner';
import { Pet } from 'src/app/models/pet';
import { PetService } from 'src/app/servicios/pet.service';

@Component({
  selector: 'app-visit-list',
  templateUrl: './visit-list.component.html',
  styleUrls: ['./visit-list.component.css']
})
export class VisitListComponent implements OnInit {

  @Input()visits:Visit[];

  @Input()mascoti:Pet;

  @Output()eliminar = new EventEmitter();

  constructor(private servicioPet:PetService,private servicioVisit:VisitService,private ruta: Router, private route: ActivatedRoute) { }

  ngOnInit() {
  }

  deleteVisit(idvisit:any){
    if(confirm("¿Desea eliminar la visita?")){
      this.servicioVisit.delVisit(idvisit).subscribe(datos =>{
        this.eliminar.emit(datos);
      });
 
    }
  }

}
