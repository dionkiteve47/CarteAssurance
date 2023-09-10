<?php

namespace App\Controller;

use App\Entity\IPAddress;
use App\Form\IPAddressType;
use App\Repository\IPAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/i/p/address')]
class IPAddressController extends AbstractController
{   


     #[Route('/profile', name: 'user_profile')]
    public function profile(): Response
    {
        // Retrieve the current user data, replace this with your actual logic
        $user = $this->getUser();

        // Render the profile template with user data
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/', name: 'app_i_p_address_index', methods: ['GET'])]
    public function index(IPAddressRepository $iPAddressRepository): Response
    {
        return $this->render('ip_address/index.html.twig', [
            'i_p_addresses' => $iPAddressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_i_p_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $iPAddress = new IPAddress();
        $form = $this->createForm(IPAddressType::class, $iPAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($iPAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_i_p_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ip_address/new.html.twig', [
            'i_p_address' => $iPAddress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_i_p_address_show', methods: ['GET'])]
    public function show(IPAddress $iPAddress): Response
    {
        return $this->render('ip_address/show.html.twig', [
            'i_p_address' => $iPAddress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_i_p_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, IPAddress $iPAddress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IPAddressType::class, $iPAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_i_p_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ip_address/edit.html.twig', [
            'i_p_address' => $iPAddress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_i_p_address_delete', methods: ['POST'])]
    public function delete(Request $request, IPAddress $iPAddress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$iPAddress->getId(), $request->request->get('_token'))) {
            $entityManager->remove($iPAddress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_i_p_address_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/searchip', name: 'search_addresses', methods: ['GET'])] 
    public function searchAddresses(Request $request, IPAddressRepository $IpAddressRepository): Response
    {   
        $searchip = $request->query->get('searchip');
       $res = $IpAddressRepository->searchByIp($searchip);
        return $this->render('ip_address/searchresults.html.twig', [
            'res' => $res
        ]);
    
    }

    public function pingAddressTest(Request $request, string $address, MailerInterface $mailer): Response
    {
        // Perform ping test for the specified IP address
        $availabilityResults = [];
            // Attempt to establish a socket connection with custom timeout
            $socket = @fsockopen($address, 443, $errno, $errstr, 10);
    
            if ($socket) {
                $availabilityResults[$address] = "Available";
                fclose($socket);
            } else {
                $availabilityResults[$address] = "Not available";
            }
        
        $isAnyIpNotAvailable = in_array("Not available", $availabilityResults);
    
    // If at least one IP is not available, send an email notification
    if ($isAnyIpNotAvailable) {
        $email = (new Email())
            ->from('carte.ping@example.com')
            ->to('salim.aloui@esprit.tn')
            ->subject('IP Address Availability Alert')
            ->text('One or more IP addresses are not available.');

        $mailer->send($email);
    }
    
        // Render the availability test results template
        return $this->render('ip_address/availability.html.twig', [
            'availabilityResults' => $availabilityResults,
        ]);
    } 
    


}
