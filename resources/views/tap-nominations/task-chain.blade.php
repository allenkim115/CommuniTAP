<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-bold text-gray-900">
                {{ __('Tap & Pass Task Chain') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 via-orange-50/30 to-teal-50/20 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Admin Info -->
            <div class="mb-8 bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20 border-l-4 border-orange-500 dark:border-orange-400 rounded-xl p-6 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-orange-900 dark:text-orange-100 mb-2">Administrator View</h3>
                        <p class="text-sm text-orange-800 dark:text-orange-200 leading-relaxed">
                            Monitor the complete Tap & Pass nomination chain to track volunteer engagement and task distribution patterns. 
                            This helps you understand how users are collaborating and passing daily tasks to each other.
                        </p>
                    </div>
                </div>
            </div>

            @if($taskChain->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-3xl shadow-2xl border-2 border-gray-200 dark:border-gray-700">
                    <div class="p-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-500 to-teal-500 flex items-center justify-center shadow-lg">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <!-- Chain icon decoration -->
                                    <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full border-2 border-orange-400 bg-white dark:bg-gray-800"></div>
                                    <div class="absolute -bottom-1 -left-1 w-6 h-6 rounded-full border-2 border-teal-400 bg-white dark:bg-gray-800"></div>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Tap & Pass Chain</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Visual chain of all accepted nominations</p>
                                </div>
                            </div>
                            <div class="px-4 py-2 bg-gradient-to-r from-orange-100 to-teal-100 dark:from-orange-900/30 dark:to-teal-900/30 rounded-xl border-2 border-orange-300 dark:border-orange-700 shadow-md">
                                <span class="text-sm font-bold text-orange-700 dark:text-orange-300">
                                    🔗 {{ $taskChain->count() }} Links
                                </span>
                            </div>
                        </div>
                        
                        <!-- Chain Reaction Visualization -->
                        <div class="mb-8 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border-2 border-orange-200 dark:border-orange-800">
                            <div class="mb-4">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Chain Reaction Visualization
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Interactive view showing how nominations spread through the community. Click on nodes or connections to see detailed information.</p>
                            </div>
                            <div id="chainReactionNetwork" style="width: 100%; height: 700px; border: 2px solid #fed7aa; border-radius: 1rem; background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 20%, #fef3c7 40%, #ecfdf5 60%, #d1fae5 80%, #ccfbf1 100%); position: relative; overflow: hidden; box-shadow: inset 0 2px 4px rgba(249, 115, 22, 0.1);">
                                <!-- Animated background gradient overlay -->
                                <div id="networkGradientOverlay" style="position: absolute; inset: 0; pointer-events: none; background: radial-gradient(circle at 20% 50%, rgba(249, 115, 22, 0.08) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(20, 184, 166, 0.08) 0%, transparent 50%); animation: gradientShift 20s ease-in-out infinite;"></div>
                                <!-- Animated background particles -->
                                <div id="networkParticles" style="position: absolute; inset: 0; pointer-events: none; opacity: 0.2;"></div>
                            </div>
                        </div>
                        
                        <!-- Nomination Details Modal -->
                        <div id="nominationModal" class="hidden fixed inset-0 z-50 overflow-y-auto" style="background-color: rgba(0, 0, 0, 0.5);" onclick="if(event.target === this) hideNominationModal();">
                            <div class="flex items-center justify-center min-h-screen p-4">
                                <div id="nominationModalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full border-2 border-orange-300 dark:border-orange-700" onclick="event.stopPropagation();">
                                    <!-- Content will be inserted by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-3xl shadow-2xl border-2 border-gray-200 dark:border-gray-700">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 mb-6 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-12 h-12 text-gray-400 dark:text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">No Task Chain Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">The Tap & Pass task chain is empty. Start the chain by completing daily tasks and nominating others!</p>
                        <a href="{{ route('tasks.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-600 to-teal-500 hover:from-orange-700 hover:to-teal-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('View Available Tasks') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- vis.js Network Library -->
    <script type="text/javascript" src="https://unpkg.com/vis-network@latest/standalone/umd/vis-network.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare chain reaction data
            @php
                $nominationsData = $taskChain->map(function($nomination) {
                    return [
                        'id' => $nomination->nominationId,
                        'nominator' => [
                            'id' => $nomination->nominator->userId,
                            'name' => $nomination->nominator->firstName . ' ' . $nomination->nominator->lastName,
                            'initials' => substr($nomination->nominator->firstName, 0, 1) . substr($nomination->nominator->lastName, 0, 1),
                            'firstName' => $nomination->nominator->firstName,
                            'lastName' => $nomination->nominator->lastName
                        ],
                        'nominee' => [
                            'id' => $nomination->nominee->userId,
                            'name' => $nomination->nominee->firstName . ' ' . $nomination->nominee->lastName,
                            'initials' => substr($nomination->nominee->firstName, 0, 1) . substr($nomination->nominee->lastName, 0, 1),
                            'firstName' => $nomination->nominee->firstName,
                            'lastName' => $nomination->nominee->lastName
                        ],
                        'task' => [
                            'id' => $nomination->task->taskId,
                            'title' => $nomination->task->title,
                            'description' => $nomination->task->description,
                            'points' => $nomination->task->points_awarded,
                            'type' => $nomination->task->task_type,
                            'location' => $nomination->task->location
                        ],
                        'date' => $nomination->nomination_date->format('Y-m-d H:i:s'),
                        'dateFormatted' => $nomination->nomination_date->format('M d, Y g:i A')
                    ];
                })->values();
            @endphp
            const nominations = @json($nominationsData);
            
            // Store nomination details by edge ID for quick lookup
            const nominationDetails = {};
            nominations.forEach(nom => {
                nominationDetails['edge_' + nom.id] = nom;
            });
            
            // Build chain reaction structure (like nuclear chain reaction)
            // Organize by generations: each person who accepts can then nominate others
            const nodes = new vis.DataSet([]);
            const edges = new vis.DataSet([]);
            const nodeMap = new Map();
            const generationMap = new Map(); // Track which generation each node belongs to
            
            // Sort nominations by date to build chronological chain
            nominations.sort((a, b) => {
                const dateA = new Date(a.date || '2000-01-01');
                const dateB = new Date(b.date || '2000-01-01');
                return dateA - dateB;
            });
            
            // Build the chain reaction tree
            // Generation 0: First nominator (the initiator)
            // Generation 1: First nominee (becomes nominator for next gen)
            // Generation 2: Next nominees, etc.
            
            let currentGeneration = 0;
            const rootNodes = [];
            
            // Find root nodes (people who nominated but weren't nominated themselves first)
            const allNominatorIds = new Set(nominations.map(n => n.nominator.id));
            const allNomineeIds = new Set(nominations.map(n => n.nominee.id));
            
            // Root nodes are nominators who weren't nominees
            nominations.forEach(nomination => {
                if (!allNomineeIds.has(nomination.nominator.id)) {
                    rootNodes.push(nomination.nominator.id);
                }
            });
            
            // If no clear root, use first nominator
            if (rootNodes.length === 0 && nominations.length > 0) {
                rootNodes.push(nominations[0].nominator.id);
            }
            
            // Build nodes and edges with generation tracking
            nominations.forEach((nomination, index) => {
                const nominatorId = 'user_' + nomination.nominator.id;
                const nomineeId = 'user_' + nomination.nominee.id;
                
                // Determine generation
                let nominatorGeneration = generationMap.get(nominatorId);
                if (nominatorGeneration === undefined) {
                    // Check if this is a root node
                    if (rootNodes.includes(nomination.nominator.id)) {
                        nominatorGeneration = 0;
                    } else {
                        // Find the generation by looking at who nominated this person
                        const previousNomination = nominations.find(n => n.nominee.id === nomination.nominator.id);
                        if (previousNomination) {
                            const prevNomineeGen = generationMap.get('user_' + previousNomination.nominee.id);
                            nominatorGeneration = prevNomineeGen !== undefined ? prevNomineeGen : currentGeneration;
                        } else {
                            nominatorGeneration = currentGeneration;
                        }
                    }
                }
                
                const nomineeGeneration = nominatorGeneration + 1;
                
                // Add nominator node with clean, modern visuals
                if (!nodeMap.has(nominatorId)) {
                    const isRoot = nominatorGeneration === 0;
                    nodes.add({
                        id: nominatorId,
                        label: nomination.nominator.initials,
                        color: {
                            background: isRoot ? '#f97316' : '#fb923c',
                            border: isRoot ? '#ea580c' : '#f97316',
                            highlight: {
                                background: isRoot ? '#ea580c' : '#f97316',
                                border: isRoot ? '#c2410c' : '#ea580c',
                                borderWidth: 4
                            }
                        },
                        shape: 'circle',
                        size: isRoot ? 50 : 42,
                        font: { 
                            color: '#ffffff', 
                            size: isRoot ? 18 : 16, 
                            face: 'Inter, sans-serif', 
                            bold: true,
                            strokeWidth: 2,
                            strokeColor: 'rgba(0,0,0,0.1)'
                        },
                        borderWidth: isRoot ? 4 : 3,
                        generation: nominatorGeneration,
                        userId: nomination.nominator.id,
                        userName: nomination.nominator.name,
                        shadow: {
                            enabled: true,
                            color: isRoot ? 'rgba(249, 115, 22, 0.4)' : 'rgba(251, 146, 60, 0.3)',
                            size: isRoot ? 12 : 10,
                            x: 2,
                            y: 2
                        }
                    });
                    nodeMap.set(nominatorId, true);
                    generationMap.set(nominatorId, nominatorGeneration);
                }
                
                // Add nominee node with clean, modern visuals
                if (!nodeMap.has(nomineeId)) {
                    nodes.add({
                        id: nomineeId,
                        label: nomination.nominee.initials,
                        color: {
                            background: '#14b8a6',
                            border: '#0d9488',
                            highlight: {
                                background: '#0d9488',
                                border: '#0f766e',
                                borderWidth: 4
                            }
                        },
                        shape: 'circle',
                        size: 42,
                        font: { 
                            color: '#ffffff', 
                            size: 16, 
                            face: 'Inter, sans-serif', 
                            bold: true,
                            strokeWidth: 2,
                            strokeColor: 'rgba(0,0,0,0.1)'
                        },
                        borderWidth: 3,
                        generation: nomineeGeneration,
                        userId: nomination.nominee.id,
                        userName: nomination.nominee.name,
                        shadow: {
                            enabled: true,
                            color: 'rgba(20, 184, 166, 0.3)',
                            size: 10,
                            x: 2,
                            y: 2
                        }
                    });
                    nodeMap.set(nomineeId, true);
                    generationMap.set(nomineeId, nomineeGeneration);
                }
                
                // Build edge label with task details
                const taskLabel = nomination.task.title.length > 20 
                    ? nomination.task.title.substring(0, 20) + '...' 
                    : nomination.task.title;
                
                // Clean edge styling with orange/teal color scheme
                const edgeColor = nomineeGeneration === 1 ? '#f97316' : '#14b8a6';
                const edgeWidth = nomineeGeneration === 1 ? 3 : 2.5;
                
                // Add edge with clean, modern visuals
                edges.add({
                    id: 'edge_' + nomination.id,
                    from: nominatorId,
                    to: nomineeId,
                    label: taskLabel,
                    arrows: { 
                        to: { 
                            enabled: true, 
                            scaleFactor: 1.5,
                            type: 'arrow',
                            length: 15,
                            width: 8
                        } 
                    },
                    color: { 
                        color: edgeColor, 
                        highlight: edgeColor === '#f97316' ? '#ea580c' : '#0d9488',
                        opacity: 0.7,
                        inherit: false
                    },
                    width: edgeWidth,
                    smooth: {
                        type: 'curvedCW',
                        roundness: 0.3
                    },
                    font: { 
                        size: 11, 
                        color: edgeColor, 
                        face: 'Inter, sans-serif', 
                        bold: true,
                        align: 'middle',
                        strokeWidth: 2,
                        strokeColor: '#ffffff'
                    },
                    dashes: false,
                    nominationId: nomination.id,
                    shadow: {
                        enabled: false
                    },
                    selectionWidth: 4
                });
            });
            
            // Clean network configuration for chain reaction layout
            const options = {
                layout: {
                    hierarchical: {
                        enabled: true,
                        direction: 'LR',
                        sortMethod: 'directed',
                        levelSeparation: 200,
                        nodeSpacing: 150,
                        treeSpacing: 200,
                        blockShifting: true,
                        edgeMinimization: true,
                        parentCentralization: true
                    }
                },
                physics: {
                    enabled: false
                },
                interaction: {
                    hover: true,
                    tooltipDelay: 200,
                    zoomView: true,
                    dragView: true,
                    selectConnectedEdges: true,
                    hoverConnectedEdges: true,
                    keyboard: true,
                    multiselect: false,
                    navigationButtons: false
                },
                nodes: {
                    font: {
                        face: 'Inter, sans-serif',
                        bold: true
                    },
                    borderWidthSelected: 5,
                    chosen: {
                        node: function(values, id, selected, hovering) {
                            if (hovering) {
                                values.size = values.size * 1.1;
                                values.borderWidth = (values.borderWidth || 3) + 2;
                            } else if (selected) {
                                values.borderWidth = (values.borderWidth || 3) + 1;
                            }
                        }
                    }
                },
                edges: {
                    font: {
                        face: 'Inter, sans-serif',
                        size: 11,
                        align: 'middle',
                        bold: true
                    },
                    labelHighlightBold: true,
                    selectionWidth: 4,
                    smooth: {
                        type: 'curvedCW',
                        roundness: 0.3
                    },
                    chosen: {
                        edge: function(values, id, selected, hovering) {
                            if (hovering) {
                                values.width = (values.width || 2.5) + 1;
                                values.color.opacity = 0.9;
                            } else if (selected) {
                                values.width = (values.width || 2.5) + 0.5;
                                values.color.opacity = 0.9;
                            }
                        }
                    }
                }
            };
            
            // Create network
            const container = document.getElementById('chainReactionNetwork');
            if (container && nodes.length > 0) {
                const data = { nodes: nodes, edges: edges };
                const network = new vis.Network(container, data, options);
                
                // Add animated particle background
                const particlesContainer = document.getElementById('networkParticles');
                if (particlesContainer) {
                    createParticleBackground(particlesContainer);
                }
                
                // Clean chain reaction animation on load
                network.on('stabilizationEnd', function() {
                    // Simple animation for nodes appearing generation by generation
                    const nodesByGeneration = {};
                    nodes.forEach(node => {
                        const gen = node.generation || 0;
                        if (!nodesByGeneration[gen]) {
                            nodesByGeneration[gen] = [];
                        }
                        nodesByGeneration[gen].push(node.id);
                    });
                    
                    // Animate each generation appearing
                    Object.keys(nodesByGeneration).sort((a, b) => a - b).forEach((gen, genIndex) => {
                        setTimeout(() => {
                            nodesByGeneration[gen].forEach((nodeId, nodeIndex) => {
                                setTimeout(() => {
                                    network.selectNodes([nodeId]);
                                    setTimeout(() => network.unselectAll(), 200);
                                }, nodeIndex * 80);
                            });
                        }, genIndex * 600);
                    });
                });
                
                // Store selected nomination for modal
                let selectedNomination = null;
                
                // Node and edge hover tooltips removed per user request
                
                // Add click event for nodes and edges
                network.on('click', function(params) {
                    if (params.nodes.length > 0) {
                        const nodeId = params.nodes[0];
                        const node = nodes.get(nodeId);
                        if (node) {
                            network.selectNodes([nodeId]);
                            
                            // Find nominations involving this user
                            const userNominations = nominations.filter(n => 
                                n.nominator.id === node.userId || n.nominee.id === node.userId
                            );
                            
                            if (userNominations.length > 0) {
                                // Show first nomination involving this user
                                selectedNomination = userNominations[0];
                                showNominationModal(selectedNomination, node);
                            }
                        }
                    } else if (params.edges.length > 0) {
                        const edgeId = params.edges[0];
                        const edge = edges.get(edgeId);
                        if (edge && edge.nominationId) {
                            network.selectEdges([edgeId]);
                            selectedNomination = nominationDetails[edgeId];
                            if (selectedNomination) {
                                showNominationModal(selectedNomination);
                            }
                        }
                    } else {
                        network.unselectAll();
                        hideNominationModal();
                    }
                });
                
                // Function to show nomination details modal
                function showNominationModal(nomination, node = null) {
                    const modal = document.getElementById('nominationModal');
                    const modalContent = document.getElementById('nominationModalContent');
                    
                    if (!modal || !modalContent) return;
                    
                    const role = node ? (node.generation === 0 ? 'Nominator' : 'Nominee') : '';
                    
                    modalContent.innerHTML = `
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Nomination Details</h3>
                                <button onclick="hideNominationModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <!-- Task Information -->
                                <div class="bg-gradient-to-r from-orange-50 to-teal-50 dark:from-orange-900/20 dark:to-teal-900/20 rounded-xl p-4 border-2 border-orange-200 dark:border-orange-800">
                                    <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 mb-2">${nomination.task.title}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">${nomination.task.description || 'No description'}</p>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center gap-1 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            ${nomination.task.points} pts
                                        </span>
                                        <span class="inline-flex items-center bg-gradient-to-r from-teal-500 to-teal-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold">
                                            ${nomination.task.type.charAt(0).toUpperCase() + nomination.task.type.slice(1)}
                                        </span>
                                        ${nomination.task.location ? `
                                            <span class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg text-xs">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                ${nomination.task.location}
                                            </span>
                                        ` : ''}
                                    </div>
                                </div>
                                
                                <!-- Users Information -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white dark:bg-gray-700 rounded-xl p-4 border-2 border-orange-300 dark:border-orange-700">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center text-white font-bold">
                                                ${nomination.nominator.initials}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-gray-100">${nomination.nominator.name}</p>
                                                <p class="text-xs text-orange-600 dark:text-orange-400">Nominator</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Completed Task</p>
                                    </div>
                                    
                                    <div class="bg-white dark:bg-gray-700 rounded-xl p-4 border-2 border-teal-300 dark:border-teal-700">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center text-white font-bold">
                                                ${nomination.nominee.initials}
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-gray-100">${nomination.nominee.name}</p>
                                                <p class="text-xs text-teal-600 dark:text-teal-400">Nominee</p>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">Accepted Task</p>
                                    </div>
                                </div>
                                
                                <!-- Date and Points -->
                                <div class="flex items-center justify-between bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border-2 border-yellow-300 dark:border-yellow-700">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nomination Date</p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">${nomination.dateFormatted}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">Points Earned</p>
                                        <p class="text-xs text-yellow-700 dark:text-yellow-400">Both users: +1 point each</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    modal.classList.remove('hidden');
                }
                
                // Function to hide modal
                window.hideNominationModal = function() {
                    const modal = document.getElementById('nominationModal');
                    if (modal) {
                        modal.classList.add('hidden');
                    }
                    network.unselectAll();
                };
            } else if (container) {
                // Show message if no data
                container.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #6b7280; font-size: 18px;">No chain reaction data available</div>';
            }
        });
    </script>

    <style>
        @keyframes chain-link-glow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(249, 115, 22, 0.3),
                            0 0 40px rgba(20, 184, 166, 0.2);
            }
            50% { 
                box-shadow: 0 0 30px rgba(249, 115, 22, 0.5),
                            0 0 60px rgba(20, 184, 166, 0.4);
            }
        }
        
        .chain-link:hover {
            animation: chain-link-glow 2s ease-in-out infinite;
        }
        
        @keyframes chain-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .chain-ring {
            animation: chain-pulse 3s ease-in-out infinite;
        }
        
        .chain-ring:nth-child(even) {
            animation-delay: 0.5s;
        }
        
        #chainReactionNetwork {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 20%, #fef3c7 40%, #ecfdf5 60%, #d1fae5 80%, #ccfbf1 100%);
            position: relative;
            transition: all 0.3s ease;
        }
        
        @keyframes gradientShift {
            0%, 100% {
                opacity: 0.5;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        #networkGradientOverlay {
            animation: gradientShift 20s ease-in-out infinite;
        }
        
        @keyframes particle-float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0.15;
            }
            50% {
                transform: translateY(-30px) translateX(20px);
                opacity: 0.3;
            }
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            animation: particle-float 15s ease-in-out infinite;
        }
        
        .particle:nth-child(odd) {
            animation-duration: 18s;
            animation-direction: reverse;
        }
        
        .particle:nth-child(3n) {
            animation-duration: 12s;
        }
        
        @keyframes node-pulse {
            0%, 100% {
                filter: drop-shadow(0 0 5px rgba(249, 115, 22, 0.5));
            }
            50% {
                filter: drop-shadow(0 0 15px rgba(249, 115, 22, 0.8));
            }
        }
        
        @keyframes edge-flow {
            0% {
                stroke-dashoffset: 0;
            }
            100% {
                stroke-dashoffset: 20;
            }
        }
        
        /* Enhanced modal styling */
        #nominationModal {
            backdrop-filter: blur(4px);
        }
        
        #nominationModalContent {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Clean hover transitions */
        .vis-network canvas {
            transition: filter 0.3s ease;
        }
    </style>
    
    <script>
        // Create subtle animated particle background
        function createParticleBackground(container) {
            const particleCount = 12;
            const colors = ['#f97316', '#fb923c', '#14b8a6', '#2dd4bf'];
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 6 + 4;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                const color = colors[Math.floor(Math.random() * colors.length)];
                particle.style.backgroundColor = color;
                particle.style.opacity = Math.random() * 0.2 + 0.1;
                particle.style.animationDelay = Math.random() * 5 + 's';
                container.appendChild(particle);
            }
        }
    </script>
</x-admin-layout>
