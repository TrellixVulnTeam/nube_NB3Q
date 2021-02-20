import { Component, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { SpecialtyService } from 'src/app/servicios/specialty.service';
import { Specialty } from 'src/app/models/specialty';

@Component({
  selector: 'app-specialties',
  templateUrl: './specialties.component.html',
  styleUrls: ['./specialties.component.css']
})
export class SpecialtiesComponent implements OnInit {

  public especialidades:Specialty[];

  constructor(private servicioSpecialty:SpecialtyService,private ruta: Router, private route: ActivatedRoute) {
    this.especialidades=[];
   }

  ngOnInit() {
    this.servicioSpecialty.getSpecialties().subscribe(datos=>{
      this.especialidades=datos;
    });
  }

}
