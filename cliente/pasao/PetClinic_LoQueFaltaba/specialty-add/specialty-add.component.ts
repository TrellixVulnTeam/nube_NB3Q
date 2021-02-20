import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { Specialty } from "../../models/specialty";
import { SpecialtyService } from "../../servicios/specialty.service";

@Component({
  selector: 'app-specialty-add',
  templateUrl: './specialty-add.component.html',
  styleUrls: ['./specialty-add.component.css']
})
export class SpecialtyAddComponent implements OnInit {
	specialty: Specialty;
  added_success: boolean = false;
  @Output() onNew = new EventEmitter<Specialty>();
  constructor(private servicioSpecialty: SpecialtyService) {
		this.specialty = <Specialty>{};
	 }

  ngOnInit() {
	}
	
	onSubmit(specialty: Specialty){
		console.log(specialty);

		specialty.id = null;
    this.servicioSpecialty.addSpecialty(specialty).subscribe(
      new_specialty => {
				console.log(new_specialty);
				
        this.specialty = new_specialty;
        this.onNew.emit(this.specialty);
      },
      error => console.log(error)
		);
  }

}
