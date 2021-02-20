import { TestBed } from '@angular/core/testing';

import { EstadocivilService } from './estadocivil.service';

describe('EstadocivilService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: EstadocivilService = TestBed.get(EstadocivilService);
    expect(service).toBeTruthy();
  });
});
