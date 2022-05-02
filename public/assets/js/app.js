const app = {
    /**
     * Méthode d'initialisation de notre module
     */
    init: function() {
        const buttons = document.querySelectorAll("button.form-group__validate--step");
        
        buttons.forEach(function (buttonNode) {
            buttonNode.addEventListener('click', app.handleNextStepClick);
        });
    },
    handleNextStepClick: function (evt) {
        const buttonNode = evt.currentTarget;

        const sectionNode = buttonNode.closest("section");

        const currentStep = parseInt(sectionNode.dataset.step);
        const nextStep = currentStep + 1;

        sectionNode.classList.remove("signup__step--active");

        let nextSectionNode;
            nextSectionNode = document.querySelector("#wizard-step" + nextStep);
            nextSectionNode.classList.add("signup__step--active");
    },
};

document.addEventListener('DOMContentLoaded', app.init);