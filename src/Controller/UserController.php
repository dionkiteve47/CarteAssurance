<?php

namespace App\Controller;


use App\Entity\IPAddress;
use App\Entity\User;
use App\Form\UserType;

use App\Repository\UserRepository;
use App\Form\EditUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/user')]

class UserController extends AbstractController 
{    

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository ): Response
    {
        $users = $userRepository->findAll();

        // Get the Doctrine connection
        $connection = $this->entityManager->getConnection();

        // Fetch IP addresses for each user and attach them to the users
        foreach ($users as $user) {
            $userId = $user->getId();

            $sql = "SELECT address FROM ipaddress 
                    WHERE id IN (SELECT ipaddress_id FROM user_ipaddress WHERE user_id = :userId)";
            $ipAddressesData = $connection->executeQuery($sql, ['userId' => $userId])->fetchAllAssociative();

            $ipAddresses = new ArrayCollection();

            foreach ($ipAddressesData as $ipAddressData) {
                $ipAddresses->add($ipAddressData['address']);
            }

            $user->setIpAddresses($ipAddresses);
        }
       
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
    
   

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {   $userId = $user->getId();
// Get the Doctrine connection
        $connection = $this->entityManager->getConnection();
        $sql = "SELECT address FROM ipaddress 
                WHERE id IN (SELECT ipaddress_id FROM user_ipaddress WHERE user_id = :userId)";
        $ipAddressesData = $connection->executeQuery($sql, ['userId' => $userId])->fetchAllAssociative();

        $ipAddresses = new ArrayCollection();

        foreach ($ipAddressesData as $ipAddressData) {
            $ipAddresses->add($ipAddressData['address']);
        }

        $user->setIpAddresses($ipAddresses);
    
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        
        $availableIPs = $entityManager->getRepository(IPAddress::class)->findAll();
        $ipAddressChoices = [];
        foreach ($availableIPs as $ipAddress) {
            $ipAddressChoices[$ipAddress->getAddress()] = $ipAddress;
        }
    
        $form = $this->createForm(EditUserFormType::class, $user, [
            'available_ip_addresses' => $ipAddressChoices,
        ]);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedIPs = $form->get('ipAddresses')->getData();
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword !== null) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword($user, $plainPassword)
                );
            }  
            // Save the new association in the user_ipaddress table
            if (!empty($selectedIPs)) {
                foreach ($selectedIPs as $ipAddress) {
                 $ip =$entityManager->getRepository(IPAddress::class)->findOneBy(['Address' => $ipAddress]);
                   if ($ip) {
                    $user->addIpAddress($ip);
                }}
            }
            // add selectedIPs to the User entity outside the loop
            $user->setIpAddresses($selectedIPs);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'app_user_delete', methods: ['GET', 'POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/search', name: 'search_users', methods: ['GET'])] 
    public function searchUsers(Request $request, UserRepository $userRepository): Response
    {   
        $searchq = $request->query->get('searchq');
       $res = $userRepository->searchByEmail($searchq);
        return $this->render('user/searchresults.html.twig', [
            'res' => $res
        ]);
    
    }

    public function pingTest(Request $request, User $user, MailerInterface $mailer): Response
    {
        // Get the user's assigned IP addresses
        $ipAddresses = $user->getIpAddresses();
        
        // Configure the timeout (in seconds)
        // You can adjust this value as needed default is 10
        $timeout = $request->query->get('timeout', 10);
        // Initialize an array to store availability results
        $availabilityResults = [];
    
        foreach ($ipAddresses as $ipAddress) {
            $ip = $ipAddress->getAddress();
    
            // Attempt to establish a socket connection with custom timeout
            $socket = @fsockopen($ip, 443, $errno, $errstr, $timeout);
    
            if ($socket) {
                $availabilityResults[$ip] = "Available";
                fclose($socket);
            } else {
                $availabilityResults[$ip] = "Not available";
            }
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
        return $this->render('user/availability.html.twig', [
            'user' => $user,
            'availabilityResults' => $availabilityResults,
            'timeout' => $timeout,
        ]);
    }
    
  

    public function testRapide(Request $request): Response
    { if (!$this->isGranted('ROLE_USER')) {
        throw new AccessDeniedException();
    }

        $user = $this->getUser(); // Get the logged-in user

        return $this->render('user/ipping.html.twig', [
        'user' => $user,
    ]);
    } 

    public function pingsTest(Request $request, User $user, MailerInterface $mailer): Response
    {
        // Get the user's assigned IP addresses
        $ipAddresses = $user->getIpAddresses();
        
        // Initialize an array to store ping results
        $timeout = $request->query->get('timeout', 10);
        // Initialize an array to store availability results
        $availabilityResults = [];
    
        foreach ($ipAddresses as $ipAddress) {
            $ip = $ipAddress->getAddress();
    
            // Attempt to establish a socket connection with custom timeout
            $socket = @fsockopen($ip, 443, $errno, $errstr, $timeout);
    
            if ($socket) {
                $availabilityResults[$ip] = "Available";
                fclose($socket);
            } else {
                $availabilityResults[$ip] = "Not available";
            }
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
        return $this->render('user/availability.html.twig', [
            'user' => $user,
            'availabilityResults' => $availabilityResults,
            'timeout' => $timeout,
        ]);
    }
  
    }
    
