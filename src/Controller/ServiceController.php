<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Service controller.
 *
 * @Route("admin/service")
 */
class ServiceController extends AbstractController
{
    /**
     * Lists all service entities.
     *
     * @Route("/", name="service_index", methods={"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $services = $em->getRepository(Service::class)->findAll();

        return $this->render('service/index.html.twig', array(
            'services' => $services,
        ));
    }

    /**
     * Creates a new service entity.
     *
     * @Route("/new", name="service_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('service_show', array('id' => $service->getId()));
        }

        return $this->render('service/new.html.twig', array(
            'service' => $service,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a service entity.
     *
     * @Route("/{id}", name="service_show", methods={"GET"})
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);

        return $this->render('service/show.html.twig', array(
            'service' => $service,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing service entity.
     *
     * @Route("/{id}/edit", name="service_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request, Service $service)
    {
        $deleteForm = $this->createDeleteForm($service);
        $editForm = $this->createForm(ServiceType::class, $service);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('service_edit', array('id' => $service->getId()));
        }

        return $this->render('service/edit.html.twig', array(
            'service' => $service,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a service entity.
     *
     * @Route("/{id}", name="service_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, Service $service)
    {
        $form = $this->createDeleteForm($service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();
        }

        return $this->redirectToRoute('service_index');
    }

    /**
     * Creates a form to delete a service entity.
     *
     * @param Service $service The service entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Service $service)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('service_delete', array('id' => $service->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
