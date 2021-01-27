import { Persona } from './../persona';
import { Component, OnInit } from '@angular/core';


@Component({
  selector: 'app-form-persona',
  templateUrl: './form-persona.component.html',
  styleUrls: ['./form-persona.component.css']
})
export class FormPersonaComponent implements OnInit {

  public persona:Persona;

  constructor() { }

  ngOnInit(): void {
  }

  addmod() {

  }

}
