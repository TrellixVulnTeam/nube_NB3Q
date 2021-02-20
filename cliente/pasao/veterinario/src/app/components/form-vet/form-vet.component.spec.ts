import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormVetComponent } from './form-vet.component';

describe('FormVetComponent', () => {
  let component: FormVetComponent;
  let fixture: ComponentFixture<FormVetComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormVetComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormVetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
