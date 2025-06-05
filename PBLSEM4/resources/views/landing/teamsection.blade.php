<!-- Team Section -->
<section id="team" class="team section light-background">

  <!-- Custom CSS for 5-Column Layout -->
  <style>
    .team-row {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
    .team-column {
      flex: 0 0 20%;
      max-width: 20%;
    }

    @media (max-width: 992px) {
      .team-column {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }

    @media (max-width: 576px) {
      .team-column {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
  </style>

  <!-- Section Title -->
  <div class="container section-title" data-aos="fade-up">
    <h2>Team</h2>
    <p>Kolaborasi, inovasi, dan semangat kerja adalah kekuatan tim kami.</p>
  </div><!-- End Section Title -->

  <div class="container" data-aos="fade-up" data-aos-delay="100">

    <div class="row team-row">
<!-- Member 1 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="100">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
      <img src="{{ asset('img/Satria.png') }}"
           class="img-fluid w-100 h-100 object-fit-cover"
           style="aspect-ratio: 3/4;"
           alt="Foto Satria">
      
        
      </div>
    <div class="team-content">
      <h4>Satria Rakhmadani</h4>
      <span class="position">Project Manager</span>
    </div>
  </div>
</div><!-- End Team Member -->


   <!-- Member 2 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="200">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/aqila.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Aqila">
     
    </div>
    <div class="team-content">
      <h4>Aqila Nur Azza</h4>
      <span class="position">Back End Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->

<!-- Member 3 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="300">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/Faiza .png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Faiza">
      
    </div>
    <div class="team-content">
      <h4>Faiza Anasthasya Eka Valen</h4>
      <span class="position">Back End Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->

<!-- Member 4 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="400">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/Lyra.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Lyra">
     
    </div>
    <div class="team-content">
      <h4>Lyra Faiqah Bilqis</h4>
      <span class="position">Front End Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->

<!-- Member 5 -->
<div class="team-column" data-aos="zoom-in" data-aos-delay="500">
  <div class="team-card">
    <div class="team-image" style="overflow: hidden;">
            <img src="{{ asset('img/Fauzi.png') }}" class="img-fluid w-100 h-100 object-fit-cover" style="aspect-ratio: 3/4;" alt="Foto Reishi">
      
    </div>
    <div class="team-content">
      <h4>M. Reishi Fauzi</h4>
      <span class="position">Front End Developer</span>
    </div>
  </div>
</div><!-- End Team Member -->


    </div>

  </div>

</section>
<!-- /Team Section -->
