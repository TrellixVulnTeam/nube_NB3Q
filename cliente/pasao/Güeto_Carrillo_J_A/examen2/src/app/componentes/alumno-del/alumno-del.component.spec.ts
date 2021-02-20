import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AlumnoDelComponent } from './alumno-del.component';

describe('AlumnoDelComponent', () => {
  let component: AlumnoDelComponent;
  let fixture: ComponentFixture<AlumnoDelComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AlumnoDelComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AlumnoDelComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
