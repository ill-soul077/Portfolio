function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
      x.className += " responsive";
    } else {
      x.className = "topnav";
    }
  }

// Single-line typewriter effect function
function typewriterEffect() {
    const texts = [
        "Software Developer",
        "Competitive Programmer"
    ];
    
    const contentElement = document.getElementById("typewriter-content");
    const cursor = document.querySelector(".typewriter-cursor");
    
    // Check if elements exist
    if (!contentElement || !cursor) {
        console.log("Typewriter elements not found");
        return;
    }
    
    let currentTextIndex = 0;
    let currentChar = 0;
    let isDeleting = false;
    let typeSpeed = 100; // Typing speed
    let deleteSpeed = 50; // Deleting speed
    let pauseAfterTyping = 2000; // Pause after typing complete word
    let pauseAfterDeleting = 500; // Pause after deleting before next word
    
    function type() {
        const currentText = texts[currentTextIndex];
        
        if (isDeleting) {
            // Deleting characters
            contentElement.textContent = currentText.substring(0, currentChar);
            currentChar--;
            
            if (currentChar < 0) {
                // Finished deleting, move to next text
                isDeleting = false;
                currentTextIndex = (currentTextIndex + 1) % texts.length; // Cycle through texts
                currentChar = 0;
                
                // Start typing next text after pause
                setTimeout(type, pauseAfterDeleting);
                return;
            }
        } else {
            // Typing characters
            contentElement.textContent = currentText.substring(0, currentChar + 1);
            currentChar++;
            
            if (currentChar === currentText.length) {
                // Finished typing current text, start deleting after pause
                setTimeout(() => {
                    isDeleting = true;
                    setTimeout(type, 100); // Small delay before starting to delete
                }, pauseAfterTyping);
                return;
            }
        }
        
        // Continue typing or deleting
        setTimeout(type, isDeleting ? deleteSpeed : typeSpeed);
    }
    
    // Start typing effect
    console.log("Starting typewriter effect");
    contentElement.textContent = "";
    cursor.style.display = 'inline';
    
    // Start the animation
    type();
}

// Initialize typewriter effect
function initTypewriter() {
    // Wait a bit for DOM to be ready
    setTimeout(typewriterEffect, 500);
}

// Start typewriter effect when page loads
document.addEventListener('DOMContentLoaded', function() {
    typewriterEffect();
});


  // Get the button
let mybutton = document.getElementById("back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      mybutton.style.display = "flex";
    } else {
      mybutton.style.display = "none";
    }
  }
  
  // When the user clicks on the button, scroll to the top of the document
  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
  



  
  let sayac=5;

function myStyle() {
    let cssStyle=document.getElementById("cssStyle");
    
    console.log(sayac);
    if(sayac==0){
        sayac=1;
        cssStyle.href="assets/css/style2.css";
        
    }
    else if(sayac==1){
      sayac=2;
      cssStyle.href="assets/css/style3.css";
      
  }
  else if(sayac==2){
    sayac=3;
    cssStyle.href="assets/css/style4.css";
    
}
else if(sayac==3){
  sayac=4;
  cssStyle.href="assets/css/style5.css";
  
}
else if(sayac==4){
  sayac=5;
  cssStyle.href="assets/css/style6.css";
  
}
    else{
        sayac=0;
        cssStyle.href="assets/css/style.css";
        
    }
    
    
  }

  


let skillsHtml=document.getElementsByClassName("skillsHtml")[0];

// Dynamic profile creation function
function createDynamicProfiles(profiles) {
    const container = document.getElementById('dynamic-profiles-container');
    if (!container) return;
    
    // Clear existing dynamic profiles
    container.innerHTML = '';
    
    // Define profiles to create dynamically
    const dynamicProfiles = ['codeforces'];
    
    dynamicProfiles.forEach(profileKey => {
        if (profiles[profileKey]) {
            const profile = profiles[profileKey];
            
            // Create the profile div
            const profileDiv = document.createElement('div');
            profileDiv.className = 'adiv';
            
            // Create icon container
            const iconDiv = document.createElement('div');
            iconDiv.className = 'idiv';
            iconDiv.style.cssText = 'width: 60px; display: flex; justify-content: center; align-items: center;';
            
            // Create icon (image or font awesome)
            if (profile.type === 'image' && profile.icon) {
                const img = document.createElement('img');
                img.height = 20;
                img.style.cssText = 'height: 20px; width: auto; object-fit: contain;';
                img.alt = profileKey.charAt(0).toUpperCase() + profileKey.slice(1);
                img.title = profileKey.charAt(0).toUpperCase() + profileKey.slice(1);
                img.src = profile.icon;
                iconDiv.appendChild(img);
            } else {
                // Fallback to icon class or default
                const icon = document.createElement('i');
                icon.className = profile.iconClass || 'fas fa-link';
                iconDiv.appendChild(icon);
            }
            
            // Create link
            const link = document.createElement('a');
            link.href = profile.url;
            link.target = '_blank';
            link.textContent = profile.username;
            link.id = profileKey + 'Html';
            
            // Append to profile div
            profileDiv.appendChild(iconDiv);
            profileDiv.appendChild(link);
            
            // Append to container
            container.appendChild(profileDiv);
        }
    });
}

let worksHtml=document.getElementsByClassName("worksHtml")[0];
let educationHtml=document.getElementsByClassName("educationHtml")[0];
let interestsHtml=document.getElementsByClassName("interestsHtml")[0];
let repositoryHtml=document.getElementsByClassName("repositoryHtml")[0];

let resume="api/portfolio.php?" + new Date().getTime(); // Add timestamp to prevent caching
const data=fetch(resume)
.then(response=> {
    console.log('API Response status:', response.status);
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    return response.text().then(text => {
        console.log('Raw API response:', text);
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('JSON parsing error:', e);
            console.error('Response text:', text);
            throw new Error('Invalid JSON response: ' + text.substring(0, 100));
        }
    });
})
.then(resume=>{
    

    let user = resume.basics;

    

    // Image is set manually in HTML, no need to update from database
    // document.getElementById("myImg").src = "assets/images/" + user.image;
    document.getElementById("name").innerHTML = user.name;

    document.getElementById("home").innerHTML = user.name;
    
    
    // Use typewriter effect instead of setting label directly
    // document.getElementById("label").innerHTML = user.label;
    // initTypewriter(); // Disabled - using inline typewriter instead

    document.getElementById("location").innerHTML = user.location.city+", "+user.location.countryCode;
    document.getElementById("email").innerHTML = user.email;
    document.getElementById("email").href="mailto:"+user.email;

    document.getElementById("twitterHtml").href=user.profiles.twitter.url;
    document.getElementById("githubHtml").href=user.profiles.github.url;
    document.getElementById("linkedinHtml").href=user.profiles.linkedin.url;

    document.getElementById("linkedinHtml").innerHTML="HASSAN";
    document.getElementById("githubHtml").innerHTML=""+user.profiles.github.username;
    document.getElementById("twitterHtml").innerHTML="@"+user.profiles.twitter.username;

    // Dynamically create additional social profiles
    createDynamicProfiles(user.profiles);

   

    document.getElementById("summary").innerHTML = user.summary;

    // Skills will be loaded by the loadSkills function below
    if (resume.skills && resume.skills.length > 0) {
        loadSkills(resume.skills);
    } 

//repositoryHtml
let repository=resume.repository;

//<p style="display=inline-block; font-size: 0.7em; font-style: italic;">${repository[i].bestLang}</p>
//

for(let i in repository){

  repositoryHtml.innerHTML +=`<hr>`;
     repositoryHtml.innerHTML +=`<div class="qr-code"><img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${repository[i].link}" alt="qr-code"></div>`;
  
  repositoryHtml.innerHTML+=`<h3 style="display:inline-block;">${repository[i].name}</h3>`;
  repositoryHtml.innerHTML+=`<a target="_blank" href="${repository[i].link}"><span class="link" style="display:inline-block;">Go Repository <i class="fa-solid fa-arrow-up-right-from-square"></i></span></a>`;
  repositoryHtml.innerHTML+=`<a target="_blank" href="${repository[i].viewLink}"><span class="link" style="display:inline-block;">View <i class="fa-solid fa-arrow-up-right-from-square"></i></span></a>`;
  repositoryHtml.innerHTML+=`<p style=" font-size: 0.7em; font-style: italic;">${repository[i].bestLang}</p>`;

  repositoryHtml.innerHTML+=`<p>${repository[i].explanation}</p>`;
  for(let j in repository[i].tag){
    repositoryHtml.innerHTML+=`<span style="display:inline-block;">${repository[i].tag[j]}</span>`;
  }
    
  
  

  

}




    

  let works=resume.work;
  for(let i in works){
    let list=works[i];
        
    worksHtml.innerHTML +=`<hr>`;
    worksHtml.innerHTML +=`<h4>${list.position} - ${list.name}</h4>`;
    
    worksHtml.innerHTML +=`<span class="worksSpan">${list.startDate} - ${list.location}</span>`;
    
    
    worksHtml.innerHTML +=`<p>${list.summary}</p>`;

    

}

//educationHtml studyType

let education=resume.education;

for(let i in education){
  let edu = education[i];
  educationHtml.innerHTML +=`<hr>`;
  educationHtml.innerHTML +=`<h3>${edu.institution}</h3>`;
  educationHtml.innerHTML +=`<h4>${edu.area} (${edu.studyType})</h4>`;
  educationHtml.innerHTML +=`<p class="worksSpan">(${edu.startDate}-${edu.endDate})</p>`;
  if(edu.gpa) {
    educationHtml.innerHTML +=`<p><strong>GPA/CGPA:</strong> ${edu.gpa}</p>`;
  }
  if(edu.courses && edu.courses.length > 0) {
    educationHtml.innerHTML +=`<p><strong>Achievements:</strong> ${edu.courses.join(', ')}</p>`;
  }
}



//interestsHtml

let interests=resume.interests;


for(let i in interests){
  interestsHtml.innerHTML +=`<span style="display:inline-block;" class="interestsSpan">${interests[i]}</span>`;

}



   
})
.catch(error => {
    console.error('Portfolio data loading error:', error);
    
    // Fallback to static JSON file
    console.log('Attempting fallback to resume.json...');
    fetch('resume.json?' + new Date().getTime())
    .then(response => response.json())
    .then(resume => {
        console.log('Fallback successful, using static JSON data');
        loadPortfolioData(resume);
    })
    .catch(fallbackError => {
        console.error('Fallback also failed:', fallbackError);
        // Show error message to user
        document.body.innerHTML = `
            <div style="text-align: center; margin-top: 100px; font-family: Arial, sans-serif;">
                <h1 style="color: #e74c3c;">Portfolio Loading Error</h1>
                <p>Unable to load portfolio data. Please check:</p>
                <ul style="display: inline-block; text-align: left;">
                    <li>XAMPP MySQL is running</li>
                    <li>Database is properly configured</li>
                    <li>API files are accessible</li>
                </ul>
                <p style="margin-top: 20px;">
                    <a href="api/portfolio.php" target="_blank" style="color: #3498db;">Test API directly</a> | 
                    <a href="admin_login.php" style="color: #3498db;">Admin Login</a>
                </p>
            </div>
        `;
    });
});

// Function to load portfolio data (extracted for reuse)
function loadPortfolioData(resume) {
    let user = resume.basics;
    
    // Image is set manually in HTML, no need to update from database
    // if (user && user.image) {
    //     // Check if image path already includes assets/images
    //     const imagePath = user.image.includes('assets/images/') ? user.image : user.image;
    //     document.getElementById("myImg").src = imagePath;
    // }
    
    if (user) {
        if (user.name) document.getElementById("name").innerHTML = user.name;
        if (user.name) document.getElementById("home").innerHTML = user.name;
        // Use typewriter effect instead of setting label directly
        // if (user.label) document.getElementById("label").innerHTML = user.label;
        // initTypewriter(); // Disabled - using inline typewriter instead
        if (user.location) {
            document.getElementById("location").innerHTML = user.location.city + ", " + user.location.countryCode;
        }
        if (user.email) {
            document.getElementById("email").innerHTML = user.email;
            document.getElementById("email").href = "mailto:" + user.email;
        }
        if (user.summary) document.getElementById("summary").innerHTML = user.summary;
        
        if (user.profiles) {
            if (user.profiles.twitter) {
                document.getElementById("twitterHtml").href = user.profiles.twitter.url;
                document.getElementById("twitterHtml").innerHTML = "@" + user.profiles.twitter.username;
            }
            if (user.profiles.github) {
                document.getElementById("githubHtml").href = user.profiles.github.url;
                document.getElementById("githubHtml").innerHTML = user.profiles.github.username;
            }
            if (user.profiles.linkedin) {
                document.getElementById("linkedinHtml").href = user.profiles.linkedin.url;
                document.getElementById("linkedinHtml").innerHTML = "HASSAN";
            }
            
            // Create dynamic profiles
            createDynamicProfiles(user.profiles);
        }
    }
    
    // Load other sections
    if (resume.skills && resume.skills.length > 0) {
        loadSkills(resume.skills);
    }
    
    if (resume.repositories && resume.repositories.length > 0) {
        loadProjects(resume.repositories);
    }
    
    if (resume.work && resume.work.length > 0) {
        loadWork(resume.work);
    }
    
    if (resume.education && resume.education.length > 0) {
        loadEducation(resume.education);
    }
}

// Function to load skills
function loadSkills(skills) {
    var skillsContent = "";
    skills.forEach(function(skill) {
        // Use 'situation' field as the category name and add data-category attribute
        const categoryName = skill.situation || skill.name || 'Other';
        skillsContent += '<div class="skills-item" data-category="' + categoryName + '"><h3>' + categoryName + '</h3>';
        
        // Parse keywords if it's a JSON string
        var keywords = [];
        if (typeof skill.keywords === 'string') {
            try {
                keywords = JSON.parse(skill.keywords);
            } catch (e) {
                keywords = skill.keywords.split(',').map(k => k.trim());
            }
        } else {
            keywords = skill.keywords || [];
        }
        
        if (keywords.length > 0) {
            skillsContent += '<div class="skills-tags">';
            keywords.forEach(function(keyword) {
                skillsContent += '<span class="skill-tag">' + keyword + '</span>';
            });
            skillsContent += '</div>';
        }
        skillsContent += '</div>';
    });
    
    // Use the global skillsHtml variable
    if (skillsHtml) {
        skillsHtml.innerHTML = skillsContent;
        // Initialize skills filtering after content is loaded
        setTimeout(() => {
            initializeSkillsFiltering();
        }, 100);
    }
}

// Skills Filtering and Statistics System
function initializeSkillsFiltering() {
    console.log('Initializing skills filtering...');
    
    const filterButtons = document.querySelectorAll('.filter-btn');
    const skillsItems = document.querySelectorAll('.skills-item');
    
    console.log('Found filter buttons:', filterButtons.length);
    console.log('Found skills items:', skillsItems.length);
    
    if (filterButtons.length === 0 || skillsItems.length === 0) {
        console.log('Skills elements not found, retrying in 500ms...');
        setTimeout(() => {
            initializeSkillsFiltering();
        }, 500);
        return;
    }
    
    // Initialize stats
    updateSkillsStats();
    
    // Add click handlers to filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            console.log('Filter clicked:', filter);
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Filter skills with animation
            filterSkills(filter);
            
            // Update stats
            updateSkillsStats(filter);
            
            // Add cool click effect
            createClickEffect(this);
        });
    });
    
    // Add hover effects to skill tags
    const skillTags = document.querySelectorAll('.skill-tag');
    skillTags.forEach(tag => {
        tag.addEventListener('mouseenter', function() {
            createSparkleEffect(this);
        });
    });
    
    console.log('Skills filtering initialized successfully!');
}

function filterSkills(filter) {
    console.log('Filtering skills by:', filter);
    const skillsItems = document.querySelectorAll('.skills-item');
    
    skillsItems.forEach(item => {
        const category = item.getAttribute('data-category');
        console.log('Item category:', category, 'Filter:', filter);
        
        if (filter === 'ALL' || category === filter) {
            item.classList.remove('hidden');
            item.style.display = 'block';
            item.style.opacity = '0';
            item.style.transform = 'translateY(10px) scale(0.95)';
            
            // Animate in
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0) scale(1)';
            }, 100);
        } else {
            item.classList.add('hidden');
            item.style.opacity = '0';
            item.style.transform = 'translateY(-10px) scale(0.95)';
            
            // Animate out
            setTimeout(() => {
                if (item.classList.contains('hidden')) {
                    item.style.display = 'none';
                }
            }, 300);
        }
    });
}

function updateSkillsStats(activeFilter = 'ALL') {
    console.log('Updating stats for filter:', activeFilter);
    
    const totalSkills = document.querySelectorAll('.skill-tag').length;
    const categoriesCount = document.querySelectorAll('.skills-item').length;
    
    let visibleSkills = totalSkills;
    let visibleCategories = categoriesCount;
    
    if (activeFilter !== 'ALL') {
        const visibleItems = document.querySelectorAll('.skills-item:not(.hidden)');
        visibleCategories = visibleItems.length;
        visibleSkills = 0;
        visibleItems.forEach(item => {
            visibleSkills += item.querySelectorAll('.skill-tag').length;
        });
    }
    
    console.log('Stats - Total skills:', totalSkills, 'Visible skills:', visibleSkills, 'Categories:', visibleCategories);
    
    // Update stats with animation
    const skillsStatElement = document.querySelector('.stat-skills .stat-number');
    const categoriesStatElement = document.querySelector('.stat-categories .stat-number');
    const experienceStatElement = document.querySelector('.stat-experience .stat-number');
    
    if (skillsStatElement) animateNumber(skillsStatElement, visibleSkills);
    if (categoriesStatElement) animateNumber(categoriesStatElement, visibleCategories);
    if (experienceStatElement) animateNumber(experienceStatElement, Math.floor(totalSkills / 3));
    
    // Update category counts
    const skillsItems = document.querySelectorAll('.skills-item');
    skillsItems.forEach(item => {
        const tagCount = item.querySelectorAll('.skill-tag').length;
        const header = item.querySelector('h3');
        if (header) {
            header.setAttribute('data-count', `${tagCount}`);
        }
    });
}

function animateNumber(element, targetNumber) {
    if (!element) return;
    
    const startNumber = parseInt(element.textContent) || 0;
    const duration = 1000;
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing function for smooth animation
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const currentNumber = Math.floor(startNumber + (targetNumber - startNumber) * easeOut);
        
        element.textContent = currentNumber;
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        } else {
            element.textContent = targetNumber;
        }
    }
    
    requestAnimationFrame(updateNumber);
}

function createClickEffect(button) {
    const effect = document.createElement('div');
    effect.style.position = 'absolute';
    effect.style.top = '50%';
    effect.style.left = '50%';
    effect.style.width = '0';
    effect.style.height = '0';
    effect.style.background = 'radial-gradient(circle, rgba(236, 179, 101, 0.8) 0%, transparent 70%)';
    effect.style.borderRadius = '50%';
    effect.style.transform = 'translate(-50%, -50%)';
    effect.style.pointerEvents = 'none';
    effect.style.zIndex = '10';
    
    button.style.position = 'relative';
    button.appendChild(effect);
    
    // Animate the effect
    effect.animate([
        { width: '0', height: '0', opacity: 1 },
        { width: '100px', height: '100px', opacity: 0 }
    ], {
        duration: 600,
        easing: 'ease-out'
    }).addEventListener('finish', () => {
        effect.remove();
    });
}

function createSparkleEffect(element) {
    const sparkle = document.createElement('div');
    sparkle.innerHTML = '✨';
    sparkle.style.position = 'absolute';
    sparkle.style.top = Math.random() * 20 - 10 + 'px';
    sparkle.style.left = Math.random() * 20 - 10 + 'px';
    sparkle.style.fontSize = '12px';
    sparkle.style.pointerEvents = 'none';
    sparkle.style.zIndex = '10';
    sparkle.style.opacity = '0';
    
    element.style.position = 'relative';
    element.appendChild(sparkle);
    
    // Animate sparkle
    sparkle.animate([
        { opacity: 0, transform: 'translateY(0) scale(0.5)' },
        { opacity: 1, transform: 'translateY(-10px) scale(1)' },
        { opacity: 0, transform: 'translateY(-20px) scale(0.5)' }
    ], {
        duration: 1000,
        easing: 'ease-out'
    }).addEventListener('finish', () => {
        sparkle.remove();
    });
}

// Function to load projects
function loadProjects(projects) {
    var projectsHtml = "";
    projects.forEach(function(project) {
        projectsHtml += '<div class="project-item">';
        projectsHtml += '<h3>' + project.name + '</h3>';
        if (project.description) {
            projectsHtml += '<p>' + project.description + '</p>';
        }
        
        if (project.url || project.github_url) {
            projectsHtml += '<div class="project-links">';
            if (project.url) {
                projectsHtml += '<a href="' + project.url + '" target="_blank">View Project</a>';
            }
            if (project.github_url) {
                projectsHtml += '<a href="' + project.github_url + '" target="_blank">GitHub</a>';
            }
            projectsHtml += '</div>';
        }
        
        // Parse tags if it's a JSON string
        var tags = [];
        if (typeof project.tags === 'string') {
            try {
                tags = JSON.parse(project.tags);
            } catch (e) {
                tags = project.tags.split(',').map(t => t.trim());
            }
        } else {
            tags = project.tags || [];
        }
        
        if (tags.length > 0) {
            projectsHtml += '<div class="project-tags">';
            tags.forEach(function(tag) {
                projectsHtml += '<span class="project-tag">' + tag + '</span>';
            });
            projectsHtml += '</div>';
        }
        
        projectsHtml += '</div>';
    });
    document.getElementById("projects").innerHTML = projectsHtml;
}

// Function to load work experience
function loadWork(work) {
    var workHtml = "";
    work.forEach(function(job) {
        workHtml += '<div class="work-item">';
        workHtml += '<h3>' + job.position + '</h3>';
        workHtml += '<h4>' + job.company + '</h4>';
        
        if (job.start_date || job.end_date) {
            workHtml += '<div class="work-dates">';
            if (job.start_date) workHtml += job.start_date;
            if (job.start_date && job.end_date) workHtml += ' - ';
            if (job.end_date) workHtml += job.end_date;
            else if (job.start_date) workHtml += ' - Present';
            workHtml += '</div>';
        }
        
        if (job.summary) {
            workHtml += '<p>' + job.summary + '</p>';
        }
        
        workHtml += '</div>';
    });
    document.getElementById("work").innerHTML = workHtml;
}

// Function to load education
function loadEducation(education) {
    var educationHtml = "";
    education.forEach(function(edu) {
        educationHtml += '<div class="education-item">';
        educationHtml += '<h3>' + edu.institution + '</h3>';
        if (edu.area) {
            educationHtml += '<h4>' + edu.area;
            if (edu.studyType) educationHtml += ' (' + edu.studyType + ')';
            educationHtml += '</h4>';
        }
        
        if (edu.startDate || edu.endDate) {
            educationHtml += '<div class="education-dates">';
            if (edu.startDate) educationHtml += edu.startDate;
            if (edu.startDate && edu.endDate) educationHtml += ' - ';
            if (edu.endDate) educationHtml += edu.endDate;
            educationHtml += '</div>';
        }
        
        if (edu.gpa) {
            educationHtml += '<p>GPA: ' + edu.gpa + '</p>';
        }
        
        if (edu.courses && edu.courses.length > 0) {
            educationHtml += '<div class="education-courses">';
            educationHtml += '<strong>Courses:</strong> ';
            educationHtml += edu.courses.join(', ');
            educationHtml += '</div>';
        }
        
        educationHtml += '</div>';
    });
    document.getElementById("education").innerHTML = educationHtml;
}

// Contact Form Handler
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const contactStatus = document.getElementById('contactStatus');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
            submitButton.disabled = true;
            
            fetch('send_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                contactStatus.style.display = 'block';
                contactStatus.className = 'contact-status ' + (data.success ? 'success' : 'error');
                contactStatus.textContent = data.message;
                
                if (data.success) {
                    contactForm.reset();
                }
            })
            .catch(error => {
                contactStatus.style.display = 'block';
                contactStatus.className = 'contact-status error';
                contactStatus.textContent = 'Network error. Please try again.';
                console.error('Contact form error:', error);
            })
            .finally(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
                
                // Hide status message after 5 seconds
                setTimeout(() => {
                    contactStatus.style.display = 'none';
                }, 5000);
            });
        });
    }
});

// Navigation active link management
document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation links
    const navLinks = document.querySelectorAll('.topnav a[href^="#"]');
    const sections = document.querySelectorAll('div[id]');
    
    // Function to update active navigation link
    function updateActiveLink() {
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 70; // Account for fixed nav height
            const sectionHeight = section.offsetHeight;
            
            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                currentSection = section.id;
            }
        });
        
        // Update navigation links
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${currentSection}`) {
                link.classList.add('active');
            }
        });
    }
    
    // Add smooth scrolling to navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            
            if (targetSection) {
                const offsetTop = targetSection.offsetTop - 60; // Account for fixed nav
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Update active link on scroll
    window.addEventListener('scroll', updateActiveLink);
    
    // Initial update
    updateActiveLink();
});

//https://www.youtube.com/watch?v=UkB-zKNBVTo&list=PLv1CRNciwsrf_DA7Yl3_kdsqYjbjbMB8r&index=3

// ======= NIM GAME LOGIC =======
class NimGame {
    constructor() {
        this.piles = [];
        this.currentPlayer = 'user'; // 'user' or 'computer'
        this.gameOver = false;
        this.moveHistory = [];
        this.score = { player: 0, computer: 0 };
        this.difficulty = 'normal'; // 'easy', 'normal', 'hard'
        this.soundEnabled = true;
        this.initializeGame();
        this.setupKeyboardControls();
    }

    initializeGame() {
        // Create 5 piles with random stones (1-10 each)
        this.piles = [];
        for (let i = 0; i < 5; i++) {
            this.piles.push(Math.floor(Math.random() * 10) + 1);
        }
        this.currentPlayer = 'user';
        this.gameOver = false;
        this.moveHistory = [];
        this.renderGame();
        this.updateStatus('YOUR TURN');
        this.logMove('⚡ BATTLE INITIATED! Click stones to begin your assault!');
        this.addSparkleEffect();
    }

    setupKeyboardControls() {
        document.addEventListener('keydown', (e) => {
            if (this.gameOver) return;
            
            // Space bar to reset game
            if (e.code === 'Space') {
                e.preventDefault();
                this.resetGame();
            }
            
            // Number keys 1-5 to select piles
            if (e.code >= 'Digit1' && e.code <= 'Digit5') {
                const pileIndex = parseInt(e.code.slice(-1)) - 1;
                if (this.piles[pileIndex] > 0) {
                    this.highlightPile(pileIndex);
                }
            }
        });
    }

    highlightPile(pileIndex) {
        const piles = document.querySelectorAll('.pile');
        piles.forEach((pile, index) => {
            pile.classList.remove('pile-highlight');
            if (index === pileIndex) {
                pile.classList.add('pile-highlight');
                setTimeout(() => pile.classList.remove('pile-highlight'), 1000);
            }
        });
    }

    addSparkleEffect() {
        const gameBoard = document.getElementById('gameBoard');
        if (!gameBoard) return;

        for (let i = 0; i < 5; i++) {
            setTimeout(() => {
                this.createSparkle(gameBoard);
            }, i * 200);
        }
    }

    createSparkle(container) {
        const sparkle = document.createElement('div');
        sparkle.className = 'sparkle';
        sparkle.style.left = Math.random() * 100 + '%';
        sparkle.style.top = Math.random() * 100 + '%';
        container.appendChild(sparkle);
        
        setTimeout(() => {
            if (sparkle.parentNode) {
                sparkle.remove();
            }
        }, 1000);
    }

    playSound(type) {
        if (!this.soundEnabled) return;
        
        // Visual sound feedback since we can't play actual sounds
        const soundIndicator = document.createElement('div');
        soundIndicator.className = `sound-effect sound-${type}`;
        soundIndicator.textContent = this.getSoundIcon(type);
        document.body.appendChild(soundIndicator);
        
        setTimeout(() => {
            if (soundIndicator.parentNode) {
                soundIndicator.remove();
            }
        }, 800);
    }

    getSoundIcon(type) {
        const icons = {
            click: '🔊',
            destroy: '💥',
            win: '🎉',
            lose: '😔',
            move: '⚡'
        };
        return icons[type] || '🔊';
    }

    renderGame() {
        const gameBoard = document.getElementById('gameBoard');
        if (!gameBoard) return; // Exit if game board doesn't exist

        gameBoard.innerHTML = '';

        this.piles.forEach((stones, pileIndex) => {
            const pileDiv = document.createElement('div');
            pileDiv.className = 'pile';
            pileDiv.innerHTML = `<div class="pile-label">PILE ${pileIndex + 1}<br>[${stones} STONES]</div>`;

            // Add pile click handler for easier mobile interaction
            pileDiv.addEventListener('dblclick', () => {
                if (stones > 0) {
                    this.makeUserMove(pileIndex, stones - 1);
                }
            });

            for (let i = 0; i < stones; i++) {
                const stone = document.createElement('div');
                stone.className = 'stone';
                stone.dataset.pile = pileIndex;
                stone.dataset.position = i;
                stone.title = `Remove ${stones - i} stone(s) from Pile ${pileIndex + 1}`;
                
                // Add stone number for better UX
                stone.innerHTML = `<span class="stone-number">${stones - i}</span>`;
                
                stone.addEventListener('click', () => this.makeUserMove(pileIndex, i));
                stone.addEventListener('mouseenter', () => {
                    this.previewMove(pileIndex, i);
                });
                stone.addEventListener('mouseleave', () => {
                    this.clearPreview();
                });
                
                pileDiv.appendChild(stone);
            }

            gameBoard.appendChild(pileDiv);
        });

        this.updatePileStats();
    }

    previewMove(pileIndex, stonePosition) {
        const stones = document.querySelectorAll(`.stone[data-pile="${pileIndex}"]`);
        const stonesToRemove = Array.from(stones).slice(stonePosition);
        
        stonesToRemove.forEach(stone => {
            stone.classList.add('stone-preview');
        });
    }

    clearPreview() {
        document.querySelectorAll('.stone-preview').forEach(stone => {
            stone.classList.remove('stone-preview');
        });
    }

    updatePileStats() {
        const totalStones = this.piles.reduce((sum, pile) => sum + pile, 0);
        const activePiles = this.piles.filter(pile => pile > 0).length;
        
        // Update arena header with stats
        const arenaText = document.querySelector('.game-board::before');
        const gameBoard = document.querySelector('.game-board');
        if (gameBoard) {
            gameBoard.setAttribute('data-stats', `${totalStones} STONES • ${activePiles} PILES ACTIVE`);
        }
    }

    makeUserMove(pileIndex, stonePosition) {
        if (this.gameOver || this.currentPlayer !== 'user') return;

        const stonesToRemove = this.piles[pileIndex] - stonePosition;
        if (stonesToRemove <= 0) return;

        this.playSound('click');
        this.clearPreview();

        // Animate stone removal
        this.animateStoneRemoval(pileIndex, stonePosition, () => {
            this.piles[pileIndex] = stonePosition;
            this.logMove(`🎯 PLAYER: Eliminated ${stonesToRemove} stone(s) from Pile ${pileIndex + 1}`);
            this.playSound('destroy');
            
            if (this.checkWinner()) {
                this.endGame('user');
                return;
            }

            this.currentPlayer = 'computer';
            this.updateStatus('CPU CALCULATING...');
            this.showThinkingAnimation();
            
            setTimeout(() => {
                this.makeComputerMove();
            }, 1200);
        });
    }

    showThinkingAnimation() {
        const statusElement = document.getElementById('gameStatus');
        if (!statusElement) return;

        let dots = '';
        const thinkingInterval = setInterval(() => {
            dots = dots.length >= 3 ? '' : dots + '.';
            statusElement.textContent = `CPU CALCULATING${dots}`;
        }, 300);

        setTimeout(() => {
            clearInterval(thinkingInterval);
        }, 1200);
    }

    animateStoneRemoval(pileIndex, keepStones, callback) {
        const stones = document.querySelectorAll(`.stone[data-pile="${pileIndex}"]`);
        const stonesToRemove = Array.from(stones).slice(keepStones);
        
        stonesToRemove.forEach((stone, index) => {
            setTimeout(() => {
                stone.classList.add('removing');
                this.createExplosionEffect(stone);
                if (index === stonesToRemove.length - 1) {
                    setTimeout(callback, 600);
                }
            }, index * 120);
        });
    }

    createExplosionEffect(stone) {
        const rect = stone.getBoundingClientRect();
        const explosion = document.createElement('div');
        explosion.className = 'explosion-effect';
        explosion.style.left = rect.left + rect.width / 2 + 'px';
        explosion.style.top = rect.top + rect.height / 2 + 'px';
        explosion.textContent = '💥';
        document.body.appendChild(explosion);
        
        setTimeout(() => {
            if (explosion.parentNode) {
                explosion.remove();
            }
        }, 600);
    }

    makeComputerMove() {
        if (this.gameOver) return;

        const move = this.getBestMove();
        const stonesToRemove = this.piles[move.pile] - move.newSize;
        
        this.playSound('move');
        
        // Animate computer move
        this.animateStoneRemoval(move.pile, move.newSize, () => {
            this.piles[move.pile] = move.newSize;
            this.logMove(`🤖 COMPUTER: Destroyed ${stonesToRemove} stone(s) from Pile ${move.pile + 1}`);
            this.playSound('destroy');
            
            if (this.checkWinner()) {
                this.endGame('computer');
                return;
            }

            this.currentPlayer = 'user';
            this.updateStatus('YOUR TURN');
        });
    }

    getBestMove() {
        // Nim optimal strategy using XOR (nim-sum)
        let nimSum = 0;
        this.piles.forEach(pile => nimSum ^= pile);

        // Add difficulty levels
        if (this.difficulty === 'easy' && Math.random() < 0.3) {
            // 30% chance to make random move on easy
            return this.getRandomMove();
        }

        // If nim-sum is 0, make any legal move
        if (nimSum === 0) {
            for (let i = 0; i < this.piles.length; i++) {
                if (this.piles[i] > 0) {
                    return { pile: i, newSize: Math.max(0, this.piles[i] - 1) };
                }
            }
        }

        // Find winning move
        for (let i = 0; i < this.piles.length; i++) {
            const target = this.piles[i] ^ nimSum;
            if (target < this.piles[i]) {
                return { pile: i, newSize: target };
            }
        }

        // Fallback: make any legal move
        return this.getRandomMove();
    }

    getRandomMove() {
        const availablePiles = this.piles
            .map((stones, index) => ({ index, stones }))
            .filter(pile => pile.stones > 0);
        
        if (availablePiles.length === 0) return { pile: 0, newSize: 0 };
        
        const randomPile = availablePiles[Math.floor(Math.random() * availablePiles.length)];
        const removeStones = Math.floor(Math.random() * randomPile.stones) + 1;
        
        return { 
            pile: randomPile.index, 
            newSize: Math.max(0, randomPile.stones - removeStones) 
        };
    }

    checkWinner() {
        return this.piles.every(pile => pile === 0);
    }

    endGame(winner) {
        this.gameOver = true;
        
        if (winner === 'user') {
            this.score.player++;
            this.playSound('win');
        } else {
            this.score.computer++;
            this.playSound('lose');
        }
        
        const message = winner === 'user' 
            ? '🎉 VICTORY ACHIEVED! 🎉<br>STRATEGIC SUPERIORITY CONFIRMED!' 
            : '💻 COMPUTER VICTORY! 💻<br>RESISTANCE IS FUTILE!';
        
        this.showWinnerMessage(message);
        this.updateStatus('BATTLE COMPLETE');
        this.logMove(`🏆 ${winner === 'user' ? 'PLAYER' : 'COMPUTER'} EMERGES VICTORIOUS!`);
        this.updateScoreDisplay();
        this.createVictoryFireworks();
    }

    createVictoryFireworks() {
        const colors = ['#ECB365', '#00ff41', '#ff6b6b', '#ffa500'];
        
        for (let i = 0; i < 10; i++) {
            setTimeout(() => {
                const firework = document.createElement('div');
                firework.className = 'firework';
                firework.style.left = Math.random() * window.innerWidth + 'px';
                firework.style.top = Math.random() * window.innerHeight + 'px';
                firework.style.color = colors[Math.floor(Math.random() * colors.length)];
                firework.textContent = '✨';
                document.body.appendChild(firework);
                
                setTimeout(() => {
                    if (firework.parentNode) {
                        firework.remove();
                    }
                }, 2000);
            }, i * 200);
        }
    }

    showWinnerMessage(message) {
        const gameScreen = document.querySelector('.game-screen');
        if (!gameScreen) return;

        const winnerDiv = document.createElement('div');
        winnerDiv.className = 'winner-message';
        winnerDiv.innerHTML = `
            <div>${message}</div>
            <div style="font-size: 16px; margin-top: 20px; color: #00ff41;">
                ⚡ INITIATE NEW BATTLE? ⚡<br>
                <span style="font-size: 12px; margin-top: 10px; display: block;">
                    Score: Player ${this.score.player} - ${this.score.computer} Computer
                </span>
            </div>
        `;
        
        // Make winner message clickable to start new game
        winnerDiv.addEventListener('click', () => {
            this.resetGame();
            winnerDiv.remove();
        });
        
        gameScreen.appendChild(winnerDiv);

        setTimeout(() => {
            if (winnerDiv.parentNode) {
                winnerDiv.remove();
            }
        }, 5000);
    }

    updateScoreDisplay() {
        // Add score display to game header if it doesn't exist
        let scoreDisplay = document.querySelector('.score-display');
        if (!scoreDisplay) {
            scoreDisplay = document.createElement('div');
            scoreDisplay.className = 'score-display';
            document.querySelector('.game-controls').appendChild(scoreDisplay);
        }
        
        scoreDisplay.innerHTML = `
            <div class="score-item">
                <span class="score-label">PLAYER</span>
                <span class="score-value">${this.score.player}</span>
            </div>
            <div class="score-separator">VS</div>
            <div class="score-item">
                <span class="score-label">CPU</span>
                <span class="score-value">${this.score.computer}</span>
            </div>
        `;
    }

    updateStatus(status) {
        const statusElement = document.getElementById('gameStatus');
        if (statusElement) {
            statusElement.textContent = status;
        }
    }

    logMove(move) {
        const logContent = document.getElementById('logContent');
        if (!logContent) return;

        const timestamp = new Date().toLocaleTimeString();
        const logEntry = document.createElement('div');
        logEntry.className = 'log-entry';
        logEntry.innerHTML = `<span style="color: #ECB365;">[${timestamp}]</span> ${move}`;
        
        // Add animation to new log entry
        logEntry.style.opacity = '0';
        logEntry.style.transform = 'translateX(-20px)';
        logContent.appendChild(logEntry);
        
        // Animate in
        setTimeout(() => {
            logEntry.style.transition = 'all 0.3s ease';
            logEntry.style.opacity = '1';
            logEntry.style.transform = 'translateX(0)';
        }, 50);
        
        logContent.scrollTop = logContent.scrollHeight;
    }

    resetGame() {
        const logContent = document.getElementById('logContent');
        if (logContent) {
            logContent.innerHTML = '';
        }
        this.initializeGame();
        console.log('Game reset');
    }
}

// Initialize the game when DOM is loaded
let nimGame;

// Wait for DOM to be ready and initialize game
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure all elements are rendered
    setTimeout(() => {
        if (document.getElementById('gameBoard')) {
            nimGame = new NimGame();
            console.log('Nim game initialized');
        }
    }, 100);
});

// Reset game function for the button
function resetGame() {
    if (nimGame) {
        nimGame.resetGame();
    } else {
        console.log('Game not initialized yet');
        // Try to initialize if not already done
        if (document.getElementById('gameBoard')) {
            nimGame = new NimGame();
        }
    }
}



